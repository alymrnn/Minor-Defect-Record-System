<script type="text/javascript">
   $(document).ready(function() {
      const now = new Date();
      if (!$("#d_year").val()) {
         $("#d_year").val(now.getFullYear());
      }
      if (!$("#d_month").val()) {
         $("#d_month").val(now.getMonth() + 1);
      }

      fetch_year_month_options().then(() => {
         generate_weekly_dashboard_filter();
      });

      setInterval(() => {
         generate_weekly_dashboard_filter();
      }, 300000); // refersh every 5 mins
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
            document.getElementById("weekly_top_lines_based_on_defect_category_chart").innerHTML = `
            <blockquote class="blockquote quote-secondary text-left m-2">
               <p id="weekly_top_lines_chart_placeholder" class="mb-0" style="font-size: 12px; color: #6c757d;">
                  Select from Weekly Record per Defect Category to generate Top 10 Lines of Selected Defect Category per Week
               </p>
            </blockquote>
         `;

            Swal.close();
         })
         .catch((error) => {
            Swal.close();
            Swal.fire("Error", "Failed to load some data: " + error, "error");
         });
   };

   const fetch_year_month_options = () => {
      return new Promise((resolve, reject) => {
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
               const currentMonth = now.getMonth() + 1;

               yearSelect.val(currentYear);
               monthSelect.val(currentMonth);

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
               reject(error);
            }
         });
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
         const year = $("#d_year").val();
         const month = $("#d_month").val();

         $.ajax({
            url: "process/weekly_monitoring_p.php",
            type: "POST",
            data: {
               method: "fetch_weekly_defect_category_count",
               year: year,
               month: month
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

               // Get unique week ranges
               const weeks = [...new Set(data.map(r => r.week_range))];

               // Prepare series data
               const seriesData = weeks.map(week => ({
                  name: week,
                  data: categories.map(cat => {
                     const found = data.find(d => d.defect_category === cat && d.week_range === week);
                     return found ? found.defect_count : 0;
                  })
               }));

               // Build dynamic chart title
               const monthNames = [
                  "January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December"
               ];
               const titleText = `Weekly Record per Defect Category (${monthNames[month - 1]} ${year})`;

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
                     text: styledChartTitle(titleText),
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
                        point: {
                           events: {
                              click: function() {
                                 const defectCategory = this.category;
                                 const selectedYear = $("#d_year").val();
                                 const selectedMonth = $("#d_month").val();

                                 Swal.fire({
                                    title: "Loading...",
                                    text: `Fetching top lines for ${defectCategory}...`,
                                    allowOutsideClick: false,
                                    background: '#1b263b',
                                    color: '#f8f9fa',
                                    didOpen: () => {
                                       Swal.showLoading();
                                    }
                                 });

                                 fetch_weekly_top_lines_based_on_defect_category(defectCategory, selectedYear, selectedMonth)
                                    .then(() => {
                                       Swal.close();
                                    })
                                    .catch(() => {
                                       Swal.fire("Error", "Failed to load data", "error");
                                    });
                              }
                           }
                        },
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
                     "#1B5E20", "#388E3C", "#81C784" // Green shades
                  ],
                  series: seriesData
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
               reject(error);
            }
         });
      });
   };

   const fetch_weekly_top_lines_based_on_defect_category = (defectCategory, selectedYear, selectedMonth) => {
      return new Promise((resolve, reject) => {
         $.ajax({
            url: "process/weekly_monitoring_p.php",
            type: "POST",
            data: {
               method: "fetch_weekly_top_lines_based_on_defect_category",
               defect_category: defectCategory,
               year: selectedYear,
               month: selectedMonth
            },
            dataType: "json",
            success: function(data) {
               $('#weekly_top_lines_chart_placeholder').hide();

               const weeks = [...new Set(data.map(r => r.week_range))];

               // 1. Compute total defects per line
               const lineTotals = {};
               data.forEach(d => {
                  lineTotals[d.line_no] = (lineTotals[d.line_no] || 0) + d.defect_count;
               });

               // 2. Sort lines by total defects (descending)
               const lines = Object.keys(lineTotals).sort((a, b) => lineTotals[b] - lineTotals[a]);

               // 3. Build series data based on sorted lines
               const seriesData = weeks.map(week => ({
                  name: week,
                  data: lines.map(line => {
                     const found = data.find(d => d.line_no === line && d.week_range === week);
                     return found ? found.defect_count : 0;
                  })
               }));

               // 🔹 Convert month number to full name
               const monthNames = [
                  "January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December"
               ];
               const monthName = monthNames[selectedMonth - 1];

               Highcharts.chart("weekly_top_lines_based_on_defect_category_chart", {
                  chart: {
                     type: "column",
                     height: "290px",
                     style: {
                        fontFamily: "Poppins, sans-serif"
                     }
                  },
                  title: {
                     useHTML: true,
                     text: styledChartTitle(`Weekly Top 10 Lines (${monthName} ${selectedYear}) with ${defectCategory}`),
                     align: "left"
                  },
                  xAxis: {
                     categories: lines,
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     labels: {
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
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '10px'
                     },
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  plotOptions: {
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.2,
                        dataLabels: {
                           enabled: true,
                           style: {
                              fontFamily: "Poppins, sans-serif",
                              fontSize: "9px"
                           }
                        }
                     }
                  },
                  colors: ["#1565C0", "#1E88E5", "#42A5F5", "#F57C00", "#FB8C00", "#FFB74D"],
                  series: seriesData
               });

               resolve();
            },
            error: function(xhr, status, error) {
               console.error("AJAX Error:", error);
               reject(error);
            }
         });
      });
   };

   const fetch_weekly_defect_per_section = () => {
      return new Promise((resolve, reject) => {
         const year = $("#d_year").val();
         const month = $("#d_month").val();

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: "POST",
            data: {
               method: "fetch_weekly_defect_per_section",
               year: year,
               month: month
            },
            dataType: "json",
            success: function(data) {
               if (data.error) {
                  console.error(data.error);
                  return;
               }

               // Extract unique sections (for x-axis)
               const rawSections = [...new Set(data.map(item => item.section))];
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

               // Build dynamic chart title
               const monthNames = [
                  "January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December"
               ];
               const titleText = `Weekly Record per Section (${monthNames[month - 1]} ${year})`;

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
                     text: styledChartTitle(titleText),
                     align: "left"
                  },
                  xAxis: {
                     categories: sections,
                     labels: {
                        style: {
                           fontFamily: "Poppins, sans-serif",
                           fontSize: "11px"
                        }
                     }
                  },
                  yAxis: {
                     min: 0,
                     labels: {
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
                     symbolHeight: 8,
                     symbolWidth: 8,
                     symbolRadius: 2
                  },
                  plotOptions: {
                     column: {
                        borderRadius: 3,
                        pointPadding: 0.2,
                        groupPadding: 0.2,
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
               reject(error);
            }
         });
      });
   };
</script>