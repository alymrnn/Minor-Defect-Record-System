<script type="text/javascript">
   $(document).ready(function() {
      fetch_line_no();

      fetch_date();
      generate_dashboard_filter();

      setInterval(() => {
         generate_dashboard_filter();
      }, 300000); //refresh rate every 5 minutes
   });

   function fetch_date() {
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
   }

   const fetch_line_no = () => {
      $.ajax({
         url: 'process/main_dashboard_p.php',
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
            fetch_top_connector_no(),
            fetch_top_defect_category_per_section(),
            fetch_defect_record_per_section()
         ])
         .then(() => {
            document.getElementById("top_overall_line_no_chart").innerHTML = `
                  <p id="overall_line_no_chart_placeholder" class="mb-0 text-info small">
                        Select from Top Defect Category Chart to generate Top Line No.'s with Selected Defect
                  </p>
            `;

            document.getElementById("top_overall_defect_details_chart").innerHTML = `
                  <p id="overall_defect_details_chart_placeholder" class="mb-0 text-info small">
                        Select from Top Defect Category Chart to generate Defect Details
                  </p>
            `;

            document.getElementById("top_line_no_section_based_chart").innerHTML = `
                  <p id="line_no_section_based_chart_placeholder" class="mb-0 text-info small">
                        Select a section from Defect Records per Section Chart to generate its Top Line No.
                  </p>
            `;

            document.getElementById("top_daily_defect_category_chart").innerHTML = `
                  <p id="defect_category_chart_placeholder" class="mb-0 text-info small">
                        Select a date from Daily Defect Trend Chart to generate Top Defect Category
                  </p>
            `;

            document.getElementById("top_daily_line_no_chart").innerHTML = `
                  <p id="line_no_chart_placeholder" class="mb-0 text-info small">
                        Select a date from Daily Defect Trend Chart to generate Top Line No.
                  </p>
            `;

            Swal.close();
         })
         .catch((error) => {
            console.error("Error loading charts:", error);
            Swal.fire('Error', 'Failed to load some charts', 'error');
         });
   };

   function styledChartTitle(text) {
      return `<span style="
              font-family: Poppins, sans-serif;
              font-weight: 600;
              font-size: 13px;
           ">${text}</span>`;
   }

   const fetch_total_defect_record = () => {
      return new Promise((resolve, reject) => {
         var line_no = $('#d_line_no').val();
         var date_from = $('#d_date_from').val();
         var date_to = $('#d_date_to').val();

         $.ajax({
            url: 'process/main_dashboard_p.php',
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
            url: 'process/main_dashboard_p.php',
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
                     type: 'column',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Defect Category Record (${date_from} - ${date_to})`),
                     align: 'left'
                  },
                  xAxis: {
                     categories: categories,
                     title: {
                        text: null,
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
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.2,
                        pointWidth: 70,
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
                                 fetch_overall_defect_details(defectCategory, date_from, date_to, line_no)
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
                     color: '#0D47A1'
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
   }

   const fetch_overall_defect_details = (defectCategory, date_from, date_to, line_no) => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: 'process/main_dashboard_p.php',
            type: 'POST',
            data: {
               method: 'fetch_overall_defect_details',
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

               $('#overall_defect_details_chart_placeholder').hide();

               const pieData = response.map(item => ({
                  name: item.defect_details,
                  y: parseInt(item.total)
               }));

               Highcharts.chart('top_overall_defect_details_chart', {
                  chart: {
                     type: 'pie',
                     height: '290px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(
                        `Defect Details Record for "${defectCategory}" (${date_from} - ${date_to})`
                     ),
                     align: 'left'
                  },
                  colors: [
                     "#1F77B4", // blue
                     "#2CA02C", // green
                     "#FF4136", // red
                     "#17BECF", // teal
                     "#0074D9", // bright blue
                     "#3D9970", // darker green/teal
                     "#FF6347", // tomato red
                     "#4E79A7", // muted blue
                     "#76B7B2", // soft teal
                     "#E15759" // soft red
                  ],
                  tooltip: {
                     useHTML: true,
                     formatter: function() {
                        return `<b>${this.point.name}</b>: ${this.y} defects (${Highcharts.numberFormat(this.percentage, 1)}%)`;
                     },
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     align: 'right',
                     verticalAlign: 'middle',
                     layout: 'vertical',
                     itemStyle: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     },
                     itemMarginTop: 0,
                     itemMarginBottom: 0,
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  plotOptions: {
                     pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        borderRadius: 3,
                        dataLabels: {
                           enabled: true,
                           format: '<b>{point.name}</b>: {point.y}',
                           style: {
                              fontFamily: 'Poppins, sans-serif',
                              fontSize: '10px'
                           }
                        },
                        showInLegend: true
                     }
                  },
                  series: [{
                     name: 'Defects',
                     colorByPoint: true,
                     data: pieData
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

   const fetch_overall_line_no = (defectCategory, date_from, date_to, line_no) => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: 'process/main_dashboard_p.php',
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

               Highcharts.chart('top_overall_line_no_chart', {
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
                        pointWidth: 14,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#0074D9'
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
            url: 'process/main_dashboard_p.php',
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
                     height: '350px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Daily Defect Record Trend (${date_from} - ${date_to})`),
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
            url: 'process/main_dashboard_p.php',
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
                     text: styledChartTitle(`Daily Top 10 Line No. (${clickedDate})`),
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
                        pointWidth: 14,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Records',
                     data: values,
                     color: '#1F77B4'
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
            url: 'process/main_dashboard_p.php',
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
                     text: styledChartTitle(`Daily Defect Category Record (${clickedDate})`),
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
                        pointWidth: 14,
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
            url: 'process/main_dashboard_p.php',
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
                        pointWidth: 14,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#42A5F5'
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
            url: 'process/main_dashboard_p.php',
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
                        pointWidth: 14,
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
            url: 'process/main_dashboard_p.php',
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
                        pointWidth: 14,
                        groupPadding: 0.5,
                        pointPadding: 0.2
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#90CAF9'
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

   const fetch_top_defect_category_per_section = () => {
      return new Promise((resolve, reject) => {
         let date_from = $('#d_date_from').val();
         let date_to = $('#d_date_to').val();

         $.ajax({
            url: "process/main_dashboard_p.php",
            type: "POST",
            dataType: "json",
            data: {
               method: "fetch_top_defect_category_per_section",
               date_from: date_from,
               date_to: date_to
            },
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               // Collect unique sections and defect categories
               let sections = [...new Set(response.map(item => item.section))];
               let defectCategories = [...new Set(response.map(item => item.defect_category))];

               // Build raw series per defect category
               let series = defectCategories.map(cat => {
                  return {
                     name: cat,
                     data: sections.map(sec => {
                        const found = response.find(r => r.section === sec && r.defect_category === cat);
                        return found ? parseInt(found.total) : 0;
                     })
                  };
               });

               // Exclude sections where all values = 0
               sections = sections.filter((sec, idx) => {
                  return series.some(s => s.data[idx] > 0);
               });

               // Rebuild series aligned to filtered sections
               series = series.map(s => {
                  return {
                     ...s,
                     data: sections.map(sec => {
                        const found = response.find(r => r.section === sec && r.defect_category === s.name);
                        return found ? parseInt(found.total) : 0;
                     })
                  };
               });

               // Exclude defect categories that are all 0
               series = series.filter(s => s.data.some(val => val > 0));

               // Chart rendering
               Highcharts.chart("top_defect_category_per_section_chart", {
                  chart: {
                     type: "column",
                     height: "350px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  colors: ["#0D47A1", "#90CAF9", "#004D40", "#009688", "#4DB6AC",
                     "#A5D6A7", "#1565C0", "#42A5F5", "#1B5E20", "#43A047",
                  ],
                  title: {
                     useHTML: true,
                     text: styledChartTitle(
                        `Top 5 Defect Categories per Section (${date_from} to ${date_to})`
                     ),
                     align: "left"
                  },
                  xAxis: {
                     categories: sections.map(sec => `Section ${sec}`),
                     title: {
                        text: null
                     },
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
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
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     },
                     stackLabels: {
                        enabled: true,
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "10px",
                           fontWeight: "bold"
                        }
                     }
                  },
                  legend: {
                     align: "right",
                     verticalAlign: "middle",
                     layout: "vertical",
                     itemStyle: {
                        fontSize: "11px",
                        fontFamily: "Poppins, sans-serif"
                     },
                     itemMarginTop: 0,
                     itemMarginBottom: 0,
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  tooltip: {
                     shared: true,
                     useHTML: true,
                     formatter: function() {
                        let header = `<b>${this.key}</b><br/>`;
                        let points = this.points
                           .filter(p => p.y > 0)
                           .map(p => `<span style="color:${p.color}">\u25CF</span> ${p.series.name}: <b>${p.y}</b>`)
                           .join("<br/>");
                        return header + points;
                     },
                     style: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "11px"
                     }
                  },
                  plotOptions: {
                     column: {
                        stacking: "normal",
                        borderRadius: 3,
                        pointPadding: 0.1,
                        groupPadding: 0.05
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  series: series
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

   const fetch_defect_record_per_section = () => {
      return new Promise((resolve, reject) => {
         let date_from = $('#d_date_from').val();
         let date_to = $('#d_date_to').val();

         $.ajax({
            url: "process/main_dashboard_p.php",
            type: "POST",
            dataType: "json",
            data: {
               method: "fetch_defect_record_per_section",
               date_from: date_from,
               date_to: date_to
            },
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               // Sort by section number (numeric)
               response.sort((a, b) => parseInt(a.section) - parseInt(b.section));

               let sections = response.map(item => item.section);
               let totals = response.map(item => parseInt(item.total));

               Highcharts.chart("defect_record_per_section_chart", {
                  chart: {
                     type: "column",
                     height: "290px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  colors: ["#1976D2"],
                  title: {
                     useHTML: true,
                     text: styledChartTitle(
                        `Defect Records per Section (${date_from} to ${date_to})`
                     ),
                     align: "left"
                  },
                  xAxis: {
                     categories: sections.map(sec => `Section ${sec}`),
                     title: {
                        text: null
                     },
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
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
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  legend: {
                     enabled: false
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  plotOptions: {
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.1,
                        groupPadding: 0.05
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  series: [{
                     name: "Record",
                     data: totals,
                     point: {
                        events: {
                           click: function() {
                              let clickedSection = this.category.replace("Section ", "");
                              fetch_top_line_no_per_section(clickedSection, date_from, date_to);
                           }
                        }
                     }
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

   const fetch_top_line_no_per_section = (section, date_from, date_to) => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: "process/main_dashboard_p.php",
            type: "POST",
            dataType: "json",
            data: {
               method: "fetch_top_line_no_per_section",
               section: section,
               date_from: date_from,
               date_to: date_to
            },
            success: function(response) {
               if (response.error) {
                  console.error(response.error);
                  reject(response.error);
                  return;
               }

               let lineNos = response.map(item => `${item.line_no}`);
               let totals = response.map(item => parseInt(item.total));

               $('#line_no_section_based_chart_placeholder').hide();

               Highcharts.chart("top_line_no_section_based_chart", {
                  chart: {
                     type: "bar",
                     height: "300px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  colors: ["#0288D1"],
                  title: {
                     useHTML: true,
                     text: styledChartTitle(
                        `Top 10 Lines in Section ${section} (${date_from} to ${date_to})`
                     ),
                     align: "left"
                  },
                  xAxis: {
                     categories: lineNos,
                     title: {
                        text: "Line No.",
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     },
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
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
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  legend: {
                     enabled: false
                  },
                  tooltip: {
                     shared: true,
                     valueSuffix: ' defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px'
                     }
                  },
                  plotOptions: {
                     bar: {
                        borderRadius: 3,
                        pointPadding: 0.1,
                        groupPadding: 0.05
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  series: [{
                     name: "Record",
                     data: totals
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

   document.addEventListener("DOMContentLoaded", function() {
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.getElementById('toggleBtn');
      const toggleIcon = toggleBtn.querySelector('i');
      const mainFooter = document.getElementById('mainFooter');

      const bsCollapse = new bootstrap.Collapse(sidebar, {
         toggle: false
      });

      toggleBtn.addEventListener('click', function() {
         const isShown = sidebar.classList.contains('show');

         if (isShown) {
            bsCollapse.hide();
            toggleIcon.classList.replace('fa-times', 'fa-bars');
            mainFooter.style.marginLeft = '0';
         } else {
            bsCollapse.show();
            toggleIcon.classList.replace('fa-bars', 'fa-times');
            mainFooter.style.marginLeft = '16.6667%';
         }
      });
   });

   document.addEventListener('scroll', function() {
      const header = document.getElementById('stickyHeader');
      if (window.scrollY > 0) {
         header.classList.add('is-sticky');
      } else {
         header.classList.remove('is-sticky');
      }
   });
</script>