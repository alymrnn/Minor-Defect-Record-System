<script type="text/javascript">
   $(document).ready(function() {
      fetch_line_no();

      // set filter date
      const today = new Date();
      const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
      const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
      const formatDate = (date) => {
         let month = String(date.getMonth() + 1).padStart(2, '0');
         let day = String(date.getDate()).padStart(2, '0');
         return `${date.getFullYear()}-${month}-${day}`;
      };

      $('#d_date_from').val(formatDate(firstDay));
      $('#d_date_to').val(formatDate(lastDay));

      generate_dashboard_filter();
   });

   const fetch_line_no = () => {
      $.ajax({
         url: 'process/dashboard_p.php',
         type: 'POST',
         data: {
            method: 'fetch_line_no'
         },
         dataType: 'json',
         success: function(response) {
            const $dropdown = $('#d_line_no');
            $dropdown.empty();

            $dropdown.append('<option value="" disabled selected>Line No.</option>');

            response.forEach(item => {
               $dropdown.append(`<option value="${item.line_no}">${item.line_no}</option>`);
            });
         },
         error: function(xhr, status, error) {
            console.error("Error fetching line numbers:", error);
         }
      });
   };

   const generate_dashboard_filter = () => {
      Swal.fire({
         text: 'Fetching data, please wait...',
         allowOutsideClick: false,
         background: '#1b263b',
         color: '#f8f9fa',
         didOpen: () => {
            Swal.showLoading();
         }
      });

      Promise.all([
            fetch_total_defect_record(),
            fetch_overall_defect_category(),
            fetch_daily_trend(),
            fetch_top_lot_no(),
            fetch_top_sequence_no(),
            fetch_top_connector_no()
         ])
         .then(() => {
            Swal.close();
         })
         .catch((error) => {
            console.error("Error loading charts:", error);
            Swal.fire('Error', 'Failed to load some charts', 'error');
         });
   };

   function styledChartTitle(text) {
      return `<span style="
              background-color: #F2F2F7; 
              padding: 3px 6px; 
              border-left: 2px solid #8d0801;
              color: #000;
              font-family: Poppins, sans-serif;
              font-weight: normal;
              font-size: 12px;
           ">${text}</span>`;
   }

   const fetch_total_defect_record = () => {
      return new Promise((resolve, reject) => {
         var line_no = $('#d_line_no').val();
         var date_from = $('#d_date_from').val();
         var date_to = $('#d_date_to').val();

         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
               method: 'fetch_total_defect_record',
               line_no: line_no,
               date_from: date_from,
               date_to: date_to
            },
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               // Current total
               $('#total_record_count').html(response.current_total);

               // --- Get previous month name dynamically ---
               let dateFrom = new Date($('#d_date_from').val());
               let prevMonth = new Date(dateFrom.getFullYear(), dateFrom.getMonth() - 1, 1);
               let prevMonthName = prevMonth.toLocaleString('default', {
                  month: 'long'
               });

               // Update label with month
               $('p[for="prev-comparison-label"]').html(`Previous Month Record - ${prevMonthName}`);

               // Calculate percentage change
               let percentageChange = 0;
               if (response.previous_total > 0) {
                  percentageChange = (((response.current_total - response.previous_total) / response.previous_total) * 100).toFixed(1);
               }

               // Previous month comparison text
               let comparisonText = "";
               if (response.comparison === "increase") {
                  comparisonText = `${response.previous_total} <br><span style="font-size:13px; color:red; font-weight: normal;">▲ Increased by ${percentageChange}%</span>`;
               } else if (response.comparison === "decrease") {
                  comparisonText = `${response.previous_total} <br><span style="font-size:13px; color:green; font-weight: normal;">▼ Decreased by ${Math.abs(percentageChange)}%</span>`;
               } else {
                  comparisonText = `${response.previous_total} <br><span style="font-size:13px; color:gray; font-weight: normal;">▬ No change</span>`;
               }

               $('#total_comparison_count').html(comparisonText);

               resolve(response);
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   };

   const fetch_overall_defect_category = () => {
      return new Promise((resolve, reject) => {
         var line_no = $('#d_line_no').val();
         var date_from = $('#d_date_from').val();
         var date_to = $('#d_date_to').val();

         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            data: {
               method: 'fetch_overall_defect_category',
               line_no: line_no,
               date_from: date_from,
               date_to: date_to
            },
            dataType: 'json',
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               const categories = response.map(item => item.defect_category);
               const values = response.map(item => parseInt(item.total));

               Highcharts.chart('top_overall_defect_category', {
                  chart: {
                     type: 'bar',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Top 10 Defect Category (${date_from} - ${date_to})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: 'Defect Category',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        enabled: true,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  },
                  plotOptions: {
                     bar: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.5,
                        pointWidth: 16,
                        cursor: 'pointer',
                        point: {
                           events: {
                              click: function() {
                                 let defectCategory = this.category;

                                 Swal.fire({
                                    title: 'Loading...',
                                    text: 'Fetching data, please wait',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                       Swal.showLoading();
                                    },
                                    background: '#1b263b',
                                    color: '#f8f9fa'
                                 });

                                 fetch_overall_line_no(defectCategory, date_from, date_to, line_no)
                                    .then(() => {
                                       Swal.close();
                                    })
                                    .catch(() => {
                                       Swal.close();
                                       Swal.fire('Error', 'Failed to load data.', 'error');
                                    });
                              }
                           }
                        }
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#339BFF'
                  }],


               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   }

   const fetch_overall_line_no = (defectCategory, date_from, date_to, line_no) => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            data: {
               method: 'fetch_overall_line_no',
               defect_category: defectCategory,
               date_from: date_from,
               date_to: date_to,
               line_no: line_no
            },
            dataType: 'json',
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               $('#overall_line_no_chart_placeholder').hide();

               const categories = response.map(item => item.line_no);
               const values = response.map(item => parseInt(item.total));

               Highcharts.chart('top_overall_line_no', {
                  chart: {
                     type: 'bar',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Top 10 Line No. with "${defectCategory}" (${date_from} - ${date_to})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: 'Line No.',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        enabled: true,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  },
                  plotOptions: {
                     bar: {
                        borderRadius: 3,
                        pointWidth: 16,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#4A90E2'
                  }]
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   };

   const fetch_daily_trend = () => {
      return new Promise((resolve, reject) => {
         var line_no = $('#d_line_no').val();
         var date_from = $('#d_date_from').val();
         var date_to = $('#d_date_to').val();

         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            data: {
               method: 'fetch_daily_trend',
               line_no: line_no,
               date_from: date_from,
               date_to: date_to
            },
            dataType: 'json',
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               // Build complete date range
               const start = new Date(date_from);
               const end = new Date(date_to);
               const dateRange = [];
               while (start <= end) {
                  let d = start.toISOString().split('T')[0];
                  dateRange.push(d);
                  start.setDate(start.getDate() + 1);
               }

               // Map response into an object for quick lookup
               const defectMap = {};
               response.forEach(item => {
                  defectMap[item.defect_date] = parseInt(item.total);
               });

               // Fill missing dates with 0
               const categories = dateRange.map(d => {
                  const parts = d.split("-"); // ["2025","09","01"]
                  return `${parts[1]}-${parts[2]}`; // "09-01"
               });
               const values = dateRange.map(d => defectMap[d] || 0);

               Highcharts.chart('daily_trend_chart', {
                  chart: {
                     type: 'area',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Daily Defect Trend (${date_from} - ${date_to})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     tickmarkPlacement: 'on',
                     labels: {
                        rotation: -45,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  },
                  plotOptions: {
                     area: {
                        marker: {
                           enabled: true,
                           radius: 5,
                           symbol: 'diamond'
                        },
                        lineWidth: 2,
                        lineColor: '#339BFF',
                        fillOpacity: 0.25
                     },
                     series: {
                        point: {
                           events: {
                              click: function() {
                                 let clickedDate = this.category;
                                 let fullDate = "2025-" + clickedDate;

                                 Swal.fire({
                                    title: 'Loading...',
                                    text: 'Fetching top records for ' + fullDate,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                       Swal.showLoading();
                                    },
                                    background: '#1b263b',
                                    color: '#f8f9fa'
                                 });

                                 Promise.all([
                                       fetch_top_daily_line_no(fullDate),
                                       fetch_top_daily_defect_category(fullDate)
                                    ])
                                    .then(() => {
                                       Swal.close();
                                    })
                                    .catch(() => {
                                       Swal.fire('Error', 'Failed to load data.', 'error');
                                    });
                              }
                           }
                        }
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#339BFF'
                  }]
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   };

   const fetch_top_daily_line_no = (clickedDate) => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_top_daily_line_no',
               defect_date: clickedDate
            },
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               const categories = response.map(item => item.line_no);
               const values = response.map(item => parseInt(item.total));

               $('#defect_category_chart_placeholder').hide();

               Highcharts.chart('top_daily_line_no_chart', {
                  chart: {
                     type: 'bar',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Top 10 Line No. (${clickedDate})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: 'Line No.',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  },
                  plotOptions: {
                     bar: {
                        borderRadius: 3,
                        pointWidth: 16,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Records',
                     data: values,
                     color: '#339BFF'
                  }]
               });

               resolve(response);
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   };

   const fetch_top_daily_defect_category = (clickedDate) => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_top_daily_defect_category',
               defect_date: clickedDate
            },
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               const categories = response.map(item => item.defect_category);
               const values = response.map(item => parseInt(item.total));

               $('#defect_category_chart_placeholder').hide();

               Highcharts.chart('top_daily_defect_category_chart', {
                  chart: {
                     type: 'bar',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Top 10 Defect Category (${clickedDate})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: 'Defect Category',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  },
                  plotOptions: {
                     bar: {
                        borderRadius: 3,
                        pointWidth: 16,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Records',
                     data: values,
                     color: '#339BFF'
                  }]
               });

               resolve(response);
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   };

   const fetch_top_lot_no = () => {
      return new Promise((resolve, reject) => {
         var line_no = $('#d_line_no').val();
         var date_from = $('#d_date_from').val();
         var date_to = $('#d_date_to').val();

         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            data: {
               method: 'fetch_top_lot_no',
               line_no: line_no,
               date_from: date_from,
               date_to: date_to
            },
            dataType: 'json',
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               const categories = response.map(item => item.lot_no);
               const values = response.map(item => parseInt(item.total));

               Highcharts.chart('top_lot_no_chart', {
                  chart: {
                     type: 'bar',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Top 10 Lot No. (${date_from} - ${date_to})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: 'Lot No.',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        enabled: true,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  },
                  plotOptions: {
                     bar: {
                        borderRadius: 3,
                        pointWidth: 16,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#339BFF'
                  }]
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   };

   const fetch_top_sequence_no = () => {
      return new Promise((resolve, reject) => {
         var line_no = $('#d_line_no').val();
         var date_from = $('#d_date_from').val();
         var date_to = $('#d_date_to').val();

         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            data: {
               method: 'fetch_top_sequence_no',
               line_no: line_no,
               date_from: date_from,
               date_to: date_to
            },
            dataType: 'json',
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               const categories = response.map(item => item.sequence_no);
               const values = response.map(item => parseInt(item.total));

               Highcharts.chart('top_sequence_no_chart', {
                  chart: {
                     type: 'bar',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Top 10 Sequence No. (${date_from} - ${date_to})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: 'Sequence No.',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        enabled: true,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  },
                  plotOptions: {
                     bar: {
                        borderRadius: 3,
                        pointWidth: 16,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#339BFF'
                  }]
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   };

   const fetch_top_connector_no = () => {
      return new Promise((resolve, reject) => {
         var line_no = $('#d_line_no').val();
         var date_from = $('#d_date_from').val();
         var date_to = $('#d_date_to').val();

         $.ajax({
            url: 'process/dashboard_p.php',
            type: 'POST',
            data: {
               method: 'fetch_top_connector_no',
               line_no: line_no,
               date_from: date_from,
               date_to: date_to
            },
            dataType: 'json',
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               const categories = response.map(item => item.connector_no);
               const values = response.map(item => parseInt(item.total));

               Highcharts.chart('top_connector_no_chart', {
                  chart: {
                     type: 'bar',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Top 10 Connector No. (${date_from} - ${date_to})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: 'Connector No.',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     },
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        enabled: true,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  },
                  plotOptions: {
                     bar: {
                        borderRadius: 3,
                        pointWidth: 16,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#339BFF'
                  }]
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", status, error);
               reject(error);
            }
         });
      });
   };
</script>