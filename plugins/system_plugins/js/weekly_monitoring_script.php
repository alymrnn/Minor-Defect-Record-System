<script type="text/javascript">
   $(document).ready(function() {
      fetch_year_month_options();
      generate_weekly_dashboard_filter();
   });

   const generate_weekly_dashboard_filter = () => {
      Swal.fire({
         title: 'Loading...',
         text: 'Fetching weekly dashboard data',
         allowOutsideClick: false,
         background: '#1b263b',
         color: '#f8f9fa',
         didOpen: () => {
            Swal.showLoading();
         }
      });

      Promise.all([
            fetch_weekly_defect_category_count(),
            fetch_weekly_defect_per_section()
         ])
         .then(() => {
            Swal.close();
         })
         .catch((error) => {
            Swal.close();
            Swal.fire("Error", "Failed to load some data: " + error, "error");
         });
   };

   const fetch_year_month_options = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: "POST",
         data: {
            method: "fetch_year_month_options"
         },
         dataType: "json",
         success: function(data) {
            const yearSelect = $("#d_year");
            const monthSelect = $("#d_month");

            yearSelect.empty();
            monthSelect.empty();

            data.years.forEach(year => {
               yearSelect.append(
                  $("<option>", {
                     value: year,
                     text: year
                  })
               );
            });

            const monthNames = [
               "January", "February", "March", "April", "May", "June",
               "July", "August", "September", "October", "November", "December"
            ];
            data.months.forEach(month => {
               monthSelect.append(
                  $("<option>", {
                     value: month,
                     text: monthNames[month - 1]
                  })
               );
            });

            const now = new Date();
            const currentYear = now.getFullYear();
            const currentMonth = now.getMonth() + 1; // 0-based → +1

            yearSelect.val(currentYear);
            monthSelect.val(currentMonth);
         },
         error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
         }
      });
   };

   function styledChartTitle(text) {
      return `<span style="
              background-color: #F2F2F7; 
              padding: 3px 6px; 
              border-left: 3px solid #1b263b;
              color: #000;
              font-family: Poppins, sans-serif;
              font-weight: normal;
              font-size: 12px;
           ">${text}</span>`;
   }

   const fetch_weekly_defect_category_count = () => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: "POST",
            data: {
               method: "fetch_weekly_defect_category_count"
            },
            dataType: "json",
            success: function(data) {
               const categories = [
                  "Exposed Wire/Junction",
                  "Missing Marking",
                  "Clamp Defect",
                  "Insufficient taping",
                  "Insufficient taping (with dimension requirement)",
                  "Missing Tape",
                  "Option Tape",
                  "Taping Defect",
                  "Damage Parts",
                  "La Terminal Defect",
                  "Wrong View",
                  "Foreign Material"
               ];

               // Get unique week ranges (for series)
               const weeks = [...new Set(data.map(r => r.week_range))];

               // Prepare series (each week = one series)
               const seriesData = weeks.map(week => ({
                  name: week,
                  data: categories.map(cat => {
                     const found = data.find(d => d.defect_category === cat && d.week_range === week);
                     return found ? found.defect_count : 0;
                  })
               }));

               Highcharts.chart("weekly_defect_category_count_chart", {
                  chart: {
                     type: "column",
                     height: "290px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle("Weekly Record per Defect Category (August 2025)"),
                     align: "left"
                  },
                  xAxis: {
                     categories: categories,
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
                        enabled: true,
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     headerFormat: "<b>{point.key}</b><br>",
                     pointFormat: "{series.name}: <b>{point.y}</b><br>",
                     style: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "11px"
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     itemStyle: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '10px'
                     },
                     itemMarginTop: 0,
                     itemMarginBottom: 0,
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  plotOptions: {
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.2,
                        cursor: "pointer",
                        dataLabels: {
                           enabled: true,
                           style: {
                              fontFamily: "Poppins, sans-serif",
                              fontSize: "9px"
                           }
                        }
                     }
                  },
                  colors: [
                     "#0D47A1", "#1976D2", "#64B5F6", // Blue shades
                     "#1B5E20", "#388E3C", "#81C784", // Green shades
                  ],
                  series: seriesData
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
            }
         });
      })
   };

   const fetch_weekly_defect_per_section = () => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: "POST",
            data: {
               method: "fetch_weekly_defect_per_section",
               year: 2025,
               month: 8
            },
            dataType: "json",
            success: function(data) {
               if (data.error) {
                  console.error(data.error);
                  return;
               }

               // Extract unique sections (for x-axis)
               const rawSections = [...new Set(data.map(item => item.section))];

               // Convert to labels like "Section 1", "Section 2"
               const sections = rawSections.map(sec => `Section ${sec}`);

               // Extract unique weeks (for series)
               const weeks = [...new Set(data.map(item => item.week_range))];

               // Build week -> section defect counts
               const weekMap = {};
               weeks.forEach(week => {
                  weekMap[week] = Array(rawSections.length).fill(0);
               });

               data.forEach(item => {
                  const sectionIndex = rawSections.indexOf(item.section);
                  const weekName = item.week_range;
                  if (sectionIndex !== -1 && weekMap[weekName]) {
                     weekMap[weekName][sectionIndex] = item.defect_count;
                  }
               });

               // Convert to Highcharts series
               const seriesData = weeks.map(week => ({
                  name: week,
                  data: weekMap[week]
               }));

               Highcharts.chart("weekly_defect_per_section_chart", {
                  chart: {
                     type: "column",
                     height: "290px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle("Weekly Record per Section (August 2025)"),
                     align: "left"
                  },
                  xAxis: {
                     categories: sections,
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
                        enabled: true,
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     headerFormat: "<b>{point.key}</b><br>",
                     pointFormat: "{series.name}: <b>{point.y}</b> defects<br>",
                     style: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "11px"
                     }
                  },
                  credits: {
                     enabled: false
                  },
                  legend: {
                     itemStyle: {
                        fontFamily: "Poppins, sans-serif",
                        fontSize: "10px"
                     },
                     itemMarginTop: 0,
                     itemMarginBottom: 0,
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  plotOptions: {
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.2,
                        cursor: "pointer",
                        dataLabels: {
                           enabled: true,
                           style: {
                              fontFamily: "Poppins, sans-serif",
                              fontSize: "9px"
                           }
                        }
                     }
                  },
                  colors: [
                     "#0D47A1", "#1976D2", "#64B5F6", // Blue shades
                     "#1B5E20", "#388E3C", "#81C784", // Green shades
                  ],
                  series: seriesData
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
            }
         });
      })
   };
</script>