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
         title: 'Loading minor dashboard...',
         allowOutsideClick: false,
         background: '#1b263b',
         color: '#f8f9fa',
         didOpen: () => {
            Swal.showLoading();
         }
      });

      Promise.all([
            fetch_total_defect_record(),
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
                  comparisonText = `${response.previous_total} <br><span style="font-size:12px; color:red;">▲ Increased by ${percentageChange}%</span>`;
               } else if (response.comparison === "decrease") {
                  comparisonText = `${response.previous_total} <br><span style="font-size:12px; color:green;">▼ Decreased by ${Math.abs(percentageChange)}%</span>`;
               } else {
                  comparisonText = `${response.previous_total} <br><span style="font-size:12px; color:gray;">▬ No change</span>`;
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
               const categories = dateRange;
               const values = dateRange.map(d => defectMap[d] || 0);

               Highcharts.chart('daily_trend_chart', {
                  chart: {
                     type: 'area',
                     height: '300px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     text: `Daily Defect Trend (${line_no} | ${date_from} - ${date_to})`,
                     align: 'left',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontWeight: 'normal',
                        fontSize: '12px'
                     }
                  },
                  xAxis: {
                     categories: categories,
                     tickmarkPlacement: 'on',
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
                        text: 'Count',
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
                           radius: 3,
                           symbol: 'diamond'
                        },
                        lineWidth: 2,
                        lineColor: '#2DC7FF',
                        fillOpacity: 0.25,
                        states: {
                           hover: {
                              lineWidth: 3
                           }
                        }
                     },
                     series: {
                        linecap: 'round'
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#2DC7FF'
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
                     height: '300px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     text: `Top 10 Lot No. Records (${line_no} | ${date_from} - ${date_to})`,
                     align: 'left',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontWeight: 'normal',
                        fontSize: '12px'
                     }
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
                        pointPadding: 0.1,
                        groupPadding: 0.05,
                        pointWidth: 18
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#2DC7FF'
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
                     height: '300px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     text: `Top 10 Sequence No. Records (${line_no} | ${date_from} - ${date_to})`,
                     align: 'left',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontWeight: 'normal',
                        fontSize: '12px'
                     }
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
                        pointPadding: 0.1,
                        groupPadding: 0.05,
                        pointWidth: 18
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#2DC7FF'
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
                     height: '300px',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  title: {
                     text: `Top 10 Connector No. Records (${line_no} | ${date_from} - ${date_to})`,
                     align: 'left',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontWeight: 'normal',
                        fontSize: '12px'
                     }
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
                        pointPadding: 0.1,
                        groupPadding: 0.05,
                        pointWidth: 18
                     }
                  },
                  series: [{
                     name: 'Record',
                     data: values,
                     color: '#2DC7FF'
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