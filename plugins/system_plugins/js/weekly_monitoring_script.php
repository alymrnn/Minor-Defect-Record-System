<script type="text/javascript">
   // $(document).ready(function() {
   //    fetch_defect_category_list();
   //    fetch_year_list();
   //    fetch_month_list();
   //    fetch_section_list();
   //    fetch_line_list();
   //    fetch_process_list();
   //    fetch_line_category_list();

   //    $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');

   //    $(document).on('change', '.defect-checkbox', function() {
   //       fetch_process_list();
   //    });

   //    setTimeout(() => {
   //       // 1. Preselect defect category checkbox
   //       $('.defect-checkbox').each(function() {
   //          if ($(this).val() === 'Exposed Wire/Junction') {
   //             $(this).prop('checked', true);
   //          }
   //       });

   //       // 2. Preselect year = 2025
   //       $('.year-check').each(function() {
   //          if ($(this).val() === '2025') {
   //             $(this).prop('checked', true);
   //          }
   //       });

   //       // 3. Preselect month = Oct
   //       $('.month-check').each(function() {
   //          const val = $(this).val().toString().toLowerCase();
   //          if (val === '10' || val === 'oct' || val === 'october') {
   //             $(this).prop('checked', true);
   //          }
   //       });

   //       // 4. Trigger dashboard filter after marking
   //       generate_weekly_dashboard_filter();
   //    }, 300); // small delay (adjust if needed)


   //    // Detect when year/month checkboxes change
   //    // $(document).on('change', '.year-check, .month-check', function() {
   //    //    const selectedYears = $('.year-check:checked').map(function() {
   //    //       return $(this).val();
   //    //    }).get();

   //    //    const selectedMonths = $('.month-check:checked').map(function() {
   //    //       return $(this).val();
   //    //    }).get();

   //    //    console.log("Selected years:", selectedYears);
   //    //    console.log("Selected months:", selectedMonths);

   //    //    if (selectedYears.length > 0 && selectedMonths.length > 0) {
   //    //       fetch_week_list(selectedYears, selectedMonths);
   //    //    } else {
   //    //       $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');
   //    //    }
   //    // });

   //    // $(document).on('change', '.process-check', function() {
   //    //    const selectedProcess = $('.process-check:checked').map(function() {
   //    //       return $(this).val();
   //    //    }).get();
   //    // });


   //    // const now = new Date();
   //    // if (!$("#d_year").val()) {
   //    //    $("#d_year").val(now.getFullYear());
   //    // }
   //    // if (!$("#d_month").val()) {
   //    //    $("#d_month").val(now.getMonth() + 1);
   //    // }

   //    // fetch_year_month_options().then(() => {
   //    //    generate_weekly_dashboard_filter();
   //    // });

   //    // setInterval(() => {
   //    //    generate_weekly_dashboard_filter();
   //    // }, 300000); // refersh every 5 mins
   // });

   $(document).ready(async function() {
      // Wait for all filter data to load (AJAX)
      await Promise.all([
         fetch_defect_category_list(),
         fetch_year_list(),
         fetch_month_list()
         // fetch_section_list()
         // fetch_line_list(),
         // fetch_process_list(),
         // fetch_line_category_list()
      ]);

      // Wait until checkboxes are actually rendered
      const waitForCheckboxes = async (selector, retries = 10, interval = 200) => {
         for (let i = 0; i < retries; i++) {
            if ($(selector).length > 0) return true;
            await new Promise(resolve => setTimeout(resolve, interval));
         }
         return false;
      };

      // Wait for all important checkbox groups to be ready
      await Promise.all([
         waitForCheckboxes('.defect-checkbox'),
         waitForCheckboxes('.year-check'),
         waitForCheckboxes('.month-check')
      ]);

      // Mark default selections
      $('.defect-checkbox').each(function() {
         if ($(this).val() === 'Exposed Wire/Junction') {
            $(this).prop('checked', true);
         }
      });

      $('.year-check').each(function() {
         if ($(this).val() === '2025') {
            $(this).prop('checked', true);
         }
      });

      $('.month-check').each(function() {
         const val = $(this).val().toString().toLowerCase();
         if (val === '10' || val === 'oct' || val === 'october') {
            $(this).prop('checked', true);
         }
      });

      // Verify all are marked before generating dashboard
      const defectChecked = $('.defect-checkbox:checked').length > 0;
      const yearChecked = $('.year-check:checked').length > 0;
      const monthChecked = $('.month-check:checked').length > 0;

      if (defectChecked && yearChecked && monthChecked) {
         generate_weekly_dashboard_filter();
      } else {
         Swal.fire({
            icon: 'info',
            title: 'Incomplete Selection',
            text: 'Please ensure defect category, year, and month are all selected before loading the dashboard.'
         });
      }

      // Re-fetch process list when defect category changes
      $(document).on('change', '.defect-checkbox', function() {
         fetch_process_list();
      });

      // Initialize empty week container
      $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');

      // get dashboard filter text
      updateSelectedFiltersDisplay();
   });

   function updateSelectedFiltersDisplay() {
      // Month number → name mapping
      const monthNames = {
         '1': 'January',
         '2': 'February',
         '3': 'March',
         '4': 'April',
         '5': 'May',
         '6': 'June',
         '7': 'July',
         '8': 'August',
         '9': 'September',
         '10': 'October',
         '11': 'November',
         '12': 'December'
      };

      // Collect selected filter values
      const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
         return $(this).val();
      }).get();

      const selectedYears = $('.year-check:checked').map(function() {
         return $(this).val();
      }).get();

      const selectedMonths = $('.month-check:checked').map(function() {
         const val = $(this).val().toString();
         // Convert numeric to month name if possible
         return monthNames[val] || val.charAt(0).toUpperCase() + val.slice(1);
      }).get();

      // Format text nicely
      let displayText = '';

      if (selectedDefectCategory.length > 0) {
         displayText += ` | ${selectedDefectCategory.join(', ')}`;
      }

      if (selectedYears.length > 0) {
         displayText += ` [${selectedYears.join(', ')}`;
         if (selectedMonths.length > 0) {
            displayText += ` - ${selectedMonths.join(', ')}`;
         }
         displayText += `]`;
      }

      // Update the span beside "Overall Monitoring"
      $('#selectedFiltersText').text(displayText);
   }

   const generate_weekly_dashboard_filter = () => {
      Swal.fire({
         title: 'Loading...',
         text: 'Fetching dashboard data',
         allowOutsideClick: false,
         background: '#1b263b',
         color: '#f8f9fa',
         didOpen: () => {
            Swal.showLoading();
         }
      });

      Promise.all([
            fetch_overall_month_week_chart(),
            fetch_harness_type_breakdown_chart(),
            fetch_overall_record_per_section_chart(),
            fetch_overall_top_ten_lines_chart(),
            fetch_summary_per_detection_chart(),
            fetch_defect_category_breakdown_chart(),
            fetch_sub_defect_details_breakdown_chart(),
            fetch_sequence_no_breakdown_chart(),
            fetch_connector_no_breakdown_chart(),
            fetch_line_category_month_week_chart()

            // fetch_weekly_defect_category_count(),
            // fetch_weekly_defect_per_section()
         ])
         .then(() => {
            // document.getElementById("weekly_top_lines_based_on_defect_category_chart").innerHTML = `
            //    <p id="weekly_top_lines_chart_placeholder" class="mb-0 text-info small">
            //       Select from Weekly Record per Defect Category to generate Top 10 Lines of Selected Defect Category per Week
            //    </p>`;

            Swal.close();
         })
         .catch((error) => {
            Swal.close();
            Swal.fire({
               icon: 'info',
               text: 'Failed to load some data: ' + error,
            });
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
              font-family: Poppins, sans-serif;
              font-weight: 600;
              font-size: 13px;
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

   document.addEventListener("DOMContentLoaded", function() {
      const sidebar = document.getElementById('sidebarOverall');
      const toggleBtn = document.getElementById('toggleBtnOverall');
      const toggleIcon = toggleBtn.querySelector('i');
      const mainFooter = document.getElementById('mainFooterOverall');

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
      const header = document.getElementById('stickyHeaderOverall');
      if (window.scrollY > 0) {
         header.classList.add('is-sticky');
      } else {
         header.classList.remove('is-sticky');
      }
   });



   // =================================================================================================================
   // OVERALL MONITORING V2
   const fetch_defect_category_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: "POST",
         data: {
            method: 'fetch_defect_category_list'
         },
         dataType: "json",
         success: function(response) {
            let container = $('#defect_category_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No categories found.</p>');
               return;
            }

            // Add Mark All / Unmark All button
            // const markAllButton = $(`
            //    <button id="markAllBtn" class="btn btn-xs btn-outline-info m-0 p-0 m-1">
            //       &nbsp;Mark All&nbsp;
            //    </button>
            // `);
            // container.append(markAllButton);

            // Generate button-style checkboxes
            response.forEach(item => {
               const buttonCheckbox = `
               <input type="checkbox" class="btn-check defect-checkbox" id="defect_${item}" value="${item}" autocomplete="off">
               <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1" for="defect_${item}">
                  &nbsp;${item}&nbsp;
               </label>
            `;
               container.append(buttonCheckbox);
            });

            // Toggle all function
            $(document).off('click', '#markAllBtn').on('click', '#markAllBtn', function() {
               const allBoxes = $('.defect-checkbox');
               const allChecked = allBoxes.filter(':checked').length === allBoxes.length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         },

         error: function(xhr, status, error) {
            console.error("Error fetching defect categories:", error);
         }
      });
   };

   const fetch_year_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: "POST",
         data: {
            method: 'fetch_year_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#year_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No year data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            // const markAllBtn = $('<button>', {
            //    class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
            //    html: '&nbsp;Mark All&nbsp;',
            //    id: 'mark_all_years_btn'
            // });
            // container.append(markAllBtn);

            // Generate year buttons
            response.forEach(year => {
               const buttonCheckbox = `
               <input type="checkbox" class="btn-check year-check" id="year_${year}" value="${year}" autocomplete="off">
               <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="year_${year}">
                  &nbsp;${year}&nbsp;
               </label>
            `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_years_btn').on('click', function() {
               const allBoxes = $('.year-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         }
      });
   };

   const fetch_month_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: "POST",
         data: {
            method: 'fetch_month_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#month_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No month data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            // const markAllBtn = $('<button>', {
            //    class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
            //    html: '&nbsp;Mark All&nbsp;',
            //    id: 'mark_all_months_btn'
            // });
            // container.append(markAllBtn);

            // Generate month buttons
            response.forEach(month => {
               const buttonCheckbox = `
               <input type="checkbox" class="btn-check month-check" id="month_${month.num}" value="${month.num}" autocomplete="off">
               <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="month_${month.num}">
                  &nbsp;${month.name}&nbsp;
               </label>
            `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_months_btn').on('click', function() {
               const allBoxes = $('.month-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }

               // Trigger week update when mark/unmark all is clicked
               const selectedYears = $('.year-check:checked').map(function() {
                  return $(this).val();
               }).get();

               const selectedMonths = $('.month-check:checked').map(function() {
                  return $(this).val();
               }).get();

               if (selectedYears.length > 0 && selectedMonths.length > 0) {
                  fetch_week_list(selectedYears, selectedMonths);
               } else {
                  $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');
               }
            });

            // Also trigger weeks when any single month checkbox is changed
            $(document).on('change', '.month-check', function() {
               const selectedYears = $('.year-check:checked').map(function() {
                  return $(this).val();
               }).get();

               const selectedMonths = $('.month-check:checked').map(function() {
                  return $(this).val();
               }).get();

               if (selectedYears.length > 0 && selectedMonths.length > 0) {
                  fetch_week_list(selectedYears, selectedMonths);
               } else {
                  $('#week_container').empty().html('<p class="text-muted text-xs">Select year and month.</p>');
               }
            });
         }
      });
   };

   const fetch_week_list = (years, months) => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_week_list',
            years: years,
            months: months
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#week_container');
            container.empty();

            if (!response || response.length === 0) {
               container.html('<p class="text-muted text-xs">No week data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_weeks_btn'
            });
            container.append(markAllBtn);

            // Loop through months
            response.forEach(monthData => {
               const monthLabel = $('<div>', {
                  class: 'mt-2 text-xs text-right',
                  text: monthData.monthLabel
               });
               container.append(monthLabel);

               // Append week buttons for this month
               monthData.weeks.forEach(week => {
                  const safeId = week.replace(/\s|\(|\)|–/g, '_');
                  const buttonCheckbox = `
                     <input type="checkbox" class="btn-check week-check" id="${safeId}" value="${week}" autocomplete="off">
                     <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1" for="${safeId}">
                     &nbsp;${week}&nbsp;
                     </label><br>
                  `;
                  container.append(buttonCheckbox);
               });
            });

            // Mark/Unmark All logic
            $('#mark_all_weeks_btn').on('click', function() {
               const allBoxes = $('.week-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         },
         error: function(xhr, status, error) {
            console.error("Error fetching weeks:", error);
         }
      });
   };

   const fetch_section_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_section_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#section_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No section data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            // const markAllBtn = $('<button>', {
            //    class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
            //    html: '&nbsp;Mark All&nbsp;',
            //    id: 'mark_all_sections_btn'
            // });
            // container.append(markAllBtn);

            // Generate section checkboxes
            response.forEach(section => {
               const safeId = section.replace(/\s+/g, '_');
               const buttonCheckbox = `
                  <input type="checkbox" class="btn-check section-check" id="section_${safeId}" value="${section}" autocomplete="off">
                  <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="section_${safeId}">
                     &nbsp;${section}&nbsp;
                  </label>
               `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_sections_btn').on('click', function() {
               const allBoxes = $('.section-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }

               fetch_line_list();
            });

            // When any section checkbox changes
            $('.section-check').on('change', fetch_line_list);
         }
      });
   };

   // Function to update line list based on selected sections
   const fetch_line_list = () => {
      const selectedSections = $('.section-check:checked').map(function() {
         return $(this).val();
      }).get();

      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_line_list',
            section: selectedSections
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#line_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No line data found for selected section(s).</p>');
               return;
            }

            // Make container scrollable and fixed height
            // container.css({
            //    'height': '250px',
            //    'overflow-y': 'auto'
            // });

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_lines_btn'
            });
            container.append(markAllBtn);

            response.forEach(line => {
               const safeId = line.replace(/\s+/g, '_');
               const buttonCheckbox = `
                  <input type="checkbox" class="btn-check line-check" id="line_${safeId}" value="${line}" autocomplete="off">
                  <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="line_${safeId}">
                     &nbsp;${line}&nbsp;
                  </label>
               `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic for line list
            $('#mark_all_lines_btn').on('click', function() {
               const allBoxes = $('.line-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         }
      });
   };

   const fetch_process_list = () => {
      // Get selected defect categories
      const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
         return $(this).val();
      }).get();

      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_process_list',
            defect_category: selectedDefectCategory
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#process_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No process data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_process_btn'
            });
            container.append(markAllBtn);

            // Generate process checkboxes
            response.forEach(process => {
               const safeId = process.replace(/\s+/g, '_').replace(/[^\w]/g, '');
               const buttonCheckbox = `
               <input type="checkbox" class="btn-check process-check" id="process_${safeId}" value="${process}" autocomplete="off">
               <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1" for="process_${safeId}">
                  &nbsp;${process}&nbsp;
               </label>
            `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_process_btn').on('click', function() {
               const allBoxes = $('.process-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         },
         error: function(xhr, status, error) {
            console.error("Error fetching process list:", error);
         }
      });
   };

   const fetch_line_category_list = () => {
      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         data: {
            method: 'fetch_line_category_list'
         },
         dataType: 'json',
         success: function(response) {
            let container = $('#line_category_container');
            container.empty();

            if (response.length === 0) {
               container.html('<p class="text-muted text-xs">No line category data found.</p>');
               return;
            }

            // Add Mark/Unmark All button
            const markAllBtn = $('<button>', {
               class: 'btn btn-xs btn-outline-info m-0 p-0 m-1',
               html: '&nbsp;Mark All&nbsp;',
               id: 'mark_all_line_category_btn'
            });
            container.append(markAllBtn);

            // Generate line catgeory checkboxes
            response.forEach(line_category => {
               const safeId = line_category.replace(/\s+/g, '_');
               const buttonCheckbox = `
                  <input type="checkbox" class="btn-check line-category-check" id="line_category_${safeId}" value="${line_category}" autocomplete="off">
                  <label class="btn btn-outline-secondary btn-xs text-xs m-0 p-0 font-weight-normal m-1 w-25" for="line_category_${safeId}">
                     &nbsp;${line_category}&nbsp;
                  </label>
               `;
               container.append(buttonCheckbox);
            });

            // Mark/Unmark All logic
            $('#mark_all_line_category_btn').on('click', function() {
               const allBoxes = $('.line-category-check');
               const allChecked = allBoxes.length === allBoxes.filter(':checked').length;

               if (allChecked) {
                  allBoxes.prop('checked', false);
                  $(this).html('&nbsp;Mark All&nbsp;');
               } else {
                  allBoxes.prop('checked', true);
                  $(this).html('&nbsp;Unmark All&nbsp;');
               }
            });
         }
      });
   };

   const fetch_overall_month_week_chart = () => {
      return new Promise((resolve, reject) => {
         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();
         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#overall_month_week_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters: years or months');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_overall_month_week_chart',
               years: selectedYears,
               months: selectedMonths
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#overall_month_week_chart').html('<p class="text-muted text-center">No data found for the selected filters.</p>');
                  resolve('No data');
                  return;
               }

               let categories = [];
               let data = [];
               let groupLabels = [];

               response.forEach(monthData => {
                  const startIndex = categories.length;
                  monthData.weeks.forEach(week => {
                     categories.push(week.week_label);
                     data.push(parseInt(week.total_records) || 0);
                  });
                  const endIndex = categories.length - 1;
                  groupLabels.push({
                     name: monthData.month.toUpperCase(),
                     from: startIndex,
                     to: endIndex
                  });
               });

               const tickPositions = groupLabels.map(g => (g.from + g.to) / 2);

               $('#overall_month_week_chart').css('min-height', '290px');

               Highcharts.chart('overall_month_week_chart', {
                  chart: {
                     type: 'column',
                     backgroundColor: '#fff'
                  },
                  title: {
                     text: 'Overall Monthly-Weekly Defect Records',
                     align: 'left',
                     style: {
                        fontSize: '13px',
                        fontWeight: '600',
                        fontFamily: 'Poppins, sans-serif',
                        color: '#17A2B8'
                     }
                  },
                  xAxis: [{
                        categories: categories,
                        tickLength: 0,
                        labels: {
                           style: {
                              fontFamily: 'Poppins, sans-serif',
                              fontSize: '11px'
                           },
                           y: 20
                        },
                        crosshair: true,
                        plotLines: groupLabels.slice(1).map(g => ({
                           color: '#DDD',
                           width: 1,
                           value: g.from - 0.5,
                           zIndex: 5
                        }))
                     },
                     {
                        linkedTo: 0,
                        opposite: false,
                        tickPositions: tickPositions,
                        labels: {
                           formatter: function() {
                              const pos = this.pos;
                              for (let i = 0; i < groupLabels.length; i++) {
                                 const g = groupLabels[i];
                                 const center = (g.from + g.to) / 2;
                                 if (Math.abs(center - pos) < 0.6) return g.name;
                              }
                              return '';
                           },
                           style: {
                              fontFamily: 'Poppins, sans-serif',
                              fontWeight: '400',
                              fontSize: '11px'
                           },
                           y: 8
                        },
                        tickLength: 0,
                        lineWidth: 0,
                        labelsAlign: 'center'
                     }
                  ],
                  yAxis: {
                     title: {
                        text: null
                     },
                     allowDecimals: false
                  },
                  tooltip: {
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     },
                     pointFormat: '<b>{point.y}</b> defects'
                  },
                  plotOptions: {
                     column: {
                        grouping: false,
                        pointPadding: 0.1,
                        borderWidth: 0
                     }
                  },
                  series: [{
                     name: 'Defect Count',
                     data: data,
                     color: '#184e77'
                  }],
                  legend: {
                     enabled: false
                  },
                  credits: {
                     enabled: false
                  },
                  responsive: {
                     rules: [{
                        condition: {
                           maxWidth: 500
                        },
                        chartOptions: {
                           xAxis: [{
                                 labels: {
                                    style: {
                                       fontFamily: 'Poppins, sans-serif',
                                       fontSize: '11px'
                                    }
                                 }
                              },
                              {
                                 labels: {
                                    style: {
                                       fontFamily: 'Poppins, sans-serif',
                                       fontSize: '11px'
                                    }
                                 }
                              }
                           ]
                        }
                     }]
                  }
               });

               resolve('Chart loaded successfully');
            },
            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   };

   const fetch_harness_type_breakdown_chart = () => {
      const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
         return $(this).val();
      }).get();

      const selectedYears = $('.year-check:checked').map(function() {
         return $(this).val();
      }).get();

      const selectedMonths = $('.month-check:checked').map(function() {
         return $(this).val();
      }).get();

      if (selectedYears.length === 0 || selectedMonths.length === 0) {
         $('#overall_month_week_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
         reject('Missing filters: years or months');
         return;
      }

      $.ajax({
         url: 'process/weekly_monitoring_p.php',
         type: 'POST',
         dataType: 'json',
         data: {
            method: 'fetch_harness_type_breakdown_chart',
            defect_category: selectedDefectCategory,
            years: selectedYears,
            months: selectedMonths
         },
         success: function(response) {
            Highcharts.chart('harness_type_breakdown_chart', {
               chart: {
                  type: 'pie',
                  backgroundColor: '#fff',
                  height: '290px'
               },
               colors: [
                  '184e77', '99d98c', '6c757d'
               ],
               title: {
                  text: 'Harness Type Breakdown',
                  align: 'left',
                  style: {
                     fontSize: '13px',
                     fontWeight: '600',
                     fontFamily: 'Poppins, sans-serif'
                  }
               },
               tooltip: {
                  pointFormatter: function() {
                     let name = this.name === 'S' ? 'SMALL' : (this.name === 'B' ? 'BIG' : this.name);
                     return `<b>${name}</b>: ${this.percentage.toFixed(1)}% (${this.y} records)`;
                  }
               },
               accessibility: {
                  point: {
                     valueSuffix: '%'
                  }
               },
               plotOptions: {
                  pie: {
                     allowPointSelect: true,
                     cursor: 'pointer',
                     dataLabels: {
                        enabled: true,
                        useHTML: true,
                        formatter: function() {
                           let name = this.point.name === 'S' ? 'SMALL' : (this.point.name === 'B' ? 'BIG' : this.point.name);
                           return `<b>${name}</b>: ${this.percentage.toFixed(1)}% (${this.y})`;
                        },
                        style: {
                           fontSize: '11px',
                           fontFamily: 'Poppins, sans-serif',
                           fontWeight: '400'
                        }
                     }
                  }
               },
               series: [{
                  name: 'Harness Type',
                  colorByPoint: true,
                  data: response.map(item => {
                     let name = item.name === 'S' ? 'S' : (item.name === 'B' ? 'B' : 'Unknown');
                     let color = name === 'B' ? '#184e77' :
                        name === 'S' ? '#99d98c' :
                        '#6c757d';
                     return {
                        name: name === 'B' ? 'BIG' : (name === 'S' ? 'SMALL' : 'Unknown'),
                        y: item.y,
                        color: color
                     };
                  })
               }],
               legend: {
                  enabled: true,
                  useHTML: true,
                  labelFormatter: function() {
                     let name = this.name === 'S' ? 'SMALL' : (this.name === 'B' ? 'BIG' : this.name);
                     return `<b>${name}</b>`;
                  },
                  itemStyle: {
                     fontFamily: 'Poppins, sans-serif',
                     fontSize: '11px',
                     fontWeight: '400'
                  }
               },
               credits: {
                  enabled: false
               }
            });
         },
         error: function(xhr, status, error) {
            console.error('Error fetching harness type breakdown:', error);
         }
      });
   };

   const fetch_overall_record_per_section_chart = () => {
      return new Promise((resolve, reject) => {
         const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#overall_record_per_section_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters: years or months');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_overall_record_per_section_chart',
               defect_category: selectedDefectCategory,
               years: selectedYears,
               months: selectedMonths
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#overall_record_per_section_chart').html('<p class="text-muted text-center">No data found for the selected filters.</p>');
                  resolve('No data');
                  return;
               }

               let categories = [];
               let groupLabels = [];
               let sectionMap = {};

               const alternatingColors = [
                  "#34a0a4", // blue
                  "#168aad", // blue
                  "#1a759f", // blue
                  "#1e6091", // blue
                  "#b5e48c", // green
                  "#99d98c", // green
                  "#76c893", // green
                  "#52b69a" // green
               ];

               const monthNamesMap = {
                  "JAN": "JANUARY",
                  "FEB": "FEBRUARY",
                  "MAR": "MARCH",
                  "APR": "APRIL",
                  "MAY": "MAY",
                  "JUN": "JUNE",
                  "JUL": "JULY",
                  "AUG": "AUGUST",
                  "SEP": "SEPTEMBER",
                  "OCT": "OCTOBER",
                  "NOV": "NOVEMBER",
                  "DEC": "DECEMBER"
               };

               response.forEach(monthData => {
                  const startIndex = categories.length;

                  // Add week labels
                  monthData.weeks.forEach(week => categories.push(week.week_label));

                  // Map sections and records
                  monthData.sections.forEach(sec => {
                     if (!sectionMap[sec.section]) sectionMap[sec.section] = [];
                     sec.weekly_records.forEach(wr => sectionMap[sec.section].push(parseInt(wr.total_records)));
                  });

                  const endIndex = categories.length - 1;

                  groupLabels.push({
                     name: monthNamesMap[monthData.month.toUpperCase()] || monthData.month.toUpperCase(),
                     from: startIndex,
                     to: endIndex
                  });
               });

               const tickPositions = groupLabels.map(g => (g.from + g.to) / 2);

               const series = Object.keys(sectionMap).map((sec, i) => ({
                  name: sec,
                  data: sectionMap[sec],
                  color: alternatingColors[i % alternatingColors.length] // keep alternating colors
               }));

               $('#overall_record_per_section_chart').css('min-height', '290px');

               Highcharts.chart('overall_record_per_section_chart', {
                  chart: {
                     type: 'column',
                     backgroundColor: '#fff'
                  },
                  colors: alternatingColors, // fallback
                  title: {
                     text: 'Overall Defect Records per Section',
                     align: 'left',
                     style: {
                        fontSize: '13px',
                        fontWeight: '600',
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  xAxis: [{
                     categories: categories,
                     tickLength: 0,
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        },
                        y: 20
                     },
                     crosshair: true,
                     plotLines: groupLabels.slice(1).map(g => ({
                        color: '#DDD',
                        width: 1,
                        value: g.from - 0.5,
                        zIndex: 5
                     }))
                  }, {
                     linkedTo: 0,
                     opposite: false,
                     tickPositions: tickPositions,
                     labels: {
                        formatter: function() {
                           const pos = this.pos;
                           for (let i = 0; i < groupLabels.length; i++) {
                              const g = groupLabels[i];
                              if (Math.abs((g.from + g.to) / 2 - pos) < 0.6) return g.name;
                           }
                           return '';
                        },
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontWeight: '400',
                           fontSize: '11px'
                        },
                        y: 8
                     },
                     tickLength: 0,
                     lineWidth: 0
                  }],
                  yAxis: {
                     allowDecimals: false,
                     title: {
                        text: null,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     useHTML: true,
                     formatter: function() {
                        let tooltip = `${this.point.category}<br/>`;
                        this.points.forEach(p => tooltip += `<span style="color:${p.color}">●</span> Section ${p.series.name}: <b>${p.y}</b><br/>`);
                        return tooltip;
                     },
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  plotOptions: {
                     column: {
                        stacking: null,
                        borderWidth: 0
                     }
                  },
                  series: series,
                  legend: {
                     enabled: true,
                     itemStyle: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px',
                        fontWeight: '400'
                     },
                     symbolHeight: 12,
                     symbolWidth: 12,
                     symbolRadius: 0,
                     squareSymbol: true,
                     labelFormatter: function() {
                        return `Section ${this.name}`;
                     }
                  },
                  credits: {
                     enabled: false
                  }
               });

               resolve('Chart loaded successfully');
            },

            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   };

   const fetch_overall_top_ten_lines_chart = () => {
      return new Promise((resolve, reject) => {
         const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#top_ten_lines_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters: years or months');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_overall_top_ten_lines_chart',
               defect_category: selectedDefectCategory,
               years: selectedYears,
               months: selectedMonths
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#top_ten_lines_chart').html('<p class="text-muted text-center">No data found for the selected filters.</p>');
                  resolve('No data');
                  return;
               }

               let categories = [];
               let groupLabels = [];
               let lineMap = {};

               const monthNamesMap = {
                  "JAN": "JANUARY",
                  "FEB": "FEBRUARY",
                  "MAR": "MARCH",
                  "APR": "APRIL",
                  "MAY": "MAY",
                  "JUN": "JUNE",
                  "JUL": "JULY",
                  "AUG": "AUGUST",
                  "SEP": "SEPTEMBER",
                  "OCT": "OCTOBER",
                  "NOV": "NOVEMBER",
                  "DEC": "DECEMBER"
               };

               response.forEach(monthData => {
                  const startIndex = categories.length;

                  // Add week labels to categories dynamically
                  monthData.weeks.forEach(week => categories.push(week.week_label));

                  // Map each line's weekly records
                  monthData.lines.forEach(line => {
                     const key = line.line_no + ' - ' + line.car_model; // NEW key
                     if (!lineMap[key]) lineMap[key] = [];

                     line.weekly_records.forEach(wr => {
                        lineMap[key].push(parseInt(wr.total_records));
                     });
                  });

                  const endIndex = categories.length - 1;
                  groupLabels.push({
                     name: monthNamesMap[monthData.month.toUpperCase()] || monthData.month.toUpperCase(),
                     from: startIndex,
                     to: endIndex
                  });
               });

               const tickPositions = groupLabels.map(g => (g.from + g.to) / 2);
               const alternatingColors = [
                  "#34a0a4", "#168aad", "#1a759f", "#1e6091", "#184e77", // blue shades
                  "#d9ed92", "#b5e48c", "#99d98c", "#76c893", "#52b69a" // green shades
               ];

               const series = Object.keys(lineMap).map((line, i) => ({
                  name: line,
                  data: lineMap[line],
                  color: alternatingColors[i % alternatingColors.length]
               }));

               $('#top_ten_lines_chart').css('min-height', '290px');

               Highcharts.chart('top_ten_lines_chart', {
                  chart: {
                     type: 'column',
                     backgroundColor: '#fff'
                  },
                  colors: alternatingColors,
                  title: {
                     text: 'Top 10 Lines',
                     align: 'left',
                     style: {
                        fontSize: '13px',
                        fontWeight: '600',
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  xAxis: [{
                     categories: categories,
                     tickLength: 0,
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        },
                        y: 20
                     },
                     crosshair: true,
                     plotLines: groupLabels.slice(1).map(g => ({
                        color: '#DDD',
                        width: 1,
                        value: g.from - 0.5,
                        zIndex: 5
                     }))
                  }, {
                     linkedTo: 0,
                     opposite: false,
                     tickPositions: tickPositions,
                     labels: {
                        formatter: function() {
                           const pos = this.pos;
                           for (let i = 0; i < groupLabels.length; i++) {
                              const g = groupLabels[i];
                              if (Math.abs((g.from + g.to) / 2 - pos) < 0.6) return g.name;
                           }
                           return '';
                        },
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontWeight: '400',
                           fontSize: '11px'
                        },
                        y: 8
                     },
                     tickLength: 0,
                     lineWidth: 0
                  }],
                  yAxis: {
                     allowDecimals: false,
                     title: {
                        text: null,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     useHTML: true,
                     formatter: function() {
                        let tooltip = `${this.points[0].category}<br/>`;

                        // Sort points descending by y value
                        let sortedPoints = this.points.slice().sort((a, b) => b.y - a.y);

                        sortedPoints.forEach(p => {
                           tooltip += `<span style="color:${p.color}">●</span> Line ${p.series.name}: <b>${p.y}</b><br/>`;
                        });

                        return tooltip;
                     },
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  plotOptions: {
                     column: {
                        stacking: null,
                        borderWidth: 0
                     }
                  },
                  series: series,
                  legend: {
                     enabled: true,
                     itemStyle: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px',
                        fontWeight: '400'
                     },
                     symbolHeight: 12,
                     symbolWidth: 12,
                     symbolRadius: 0,
                     squareSymbol: true,
                     labelFormatter: function() {
                        return `Line ${this.name}`;
                     }
                  },
                  credits: {
                     enabled: false
                  }
               });

               resolve('Top 10 lines chart loaded');
            },
            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   };

   const fetch_summary_per_detection_chart = () => {
      return new Promise((resolve, reject) => {
         const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#summary_per_detection_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_summary_per_detection_chart',
               years: selectedYears,
               months: selectedMonths,
               defect_category: selectedDefectCategory
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#summary_per_detection_chart').html('<p class="text-muted text-center">No data found for the selected filters.</p>');
                  resolve('No data');
                  return;
               }

               const categories = response.map(r => r.process);
               const data = response.map(r => parseInt(r.total_records));

               Highcharts.chart('summary_per_detection_chart', {
                  chart: {
                     type: 'bar',
                     backgroundColor: '#fff',
                     height: '290px'
                  },
                  title: {
                     text: 'Summary per Detection',
                     align: 'left',
                     style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '13px',
                        fontWeight: '600'
                     }
                  },
                  xAxis: {
                     categories: categories,
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
                  yAxis: {
                     min: 0,
                     allowDecimals: false,
                     title: {
                        text: null
                     },
                     labels: {
                        overflow: 'justify'
                     }
                  },
                  tooltip: {
                     pointFormat: '<b>{point.y}</b> records',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  plotOptions: {
                     bar: {
                        dataLabels: {
                           enabled: false
                        }
                     }
                  },
                  series: [{
                     name: 'Records',
                     data: data,
                     color: '#76c893'
                  }],
                  legend: {
                     enabled: false
                  },
                  credits: {
                     enabled: false
                  }
               });

               resolve('Summary per detection chart loaded');
            },
            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   };

   const fetch_defect_category_breakdown_chart = () => {
      return new Promise((resolve, reject) => {
         // Get selected defect categories
         const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedDefectCategory.length === 0) {
            $('#defect_category_breakdown_chart').html('<p class="text-info text-center text-sm">Please select at least one defect category.</p>');
            reject('No defect category selected');
            return;
         }

         // Get selected years and months
         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();
         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#defect_category_breakdown_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters: years or months');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_defect_category_breakdown_chart',
               defect_category: selectedDefectCategory,
               years: selectedYears,
               months: selectedMonths
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#defect_category_breakdown_chart').html('<p class="text-muted text-center">No data found for the selected filters.</p>');
                  resolve('No data');
                  return;
               }

               let categories = [];
               let data = [];
               let groupLabels = [];

               response.forEach(monthData => {
                  const startIndex = categories.length;
                  monthData.weeks.forEach(week => {
                     categories.push(week.week_label);
                     data.push(parseInt(week.total_records) || 0);
                  });
                  const endIndex = categories.length - 1;
                  groupLabels.push({
                     name: monthData.month.toUpperCase(),
                     from: startIndex,
                     to: endIndex
                  });
               });

               const tickPositions = groupLabels.map(g => (g.from + g.to) / 2);
               $('#defect_category_breakdown_chart').css('min-height', '290px');

               Highcharts.chart('defect_category_breakdown_chart', {
                  chart: {
                     type: 'column',
                     backgroundColor: '#fff'
                  },
                  title: {
                     text: selectedDefectCategory.join(', ') + ' Weekly Record Breakdown', // <-- dynamic title
                     align: 'left',
                     style: {
                        fontSize: '13px',
                        fontWeight: '600',
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  xAxis: [{
                     categories: categories,
                     tickLength: 0,
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        },
                        y: 20
                     },
                     crosshair: true,
                     plotLines: groupLabels.slice(1).map(g => ({
                        color: '#DDD',
                        width: 1,
                        value: g.from - 0.5,
                        zIndex: 5
                     }))
                  }, {
                     linkedTo: 0,
                     opposite: false,
                     tickPositions: tickPositions,
                     labels: {
                        formatter: function() {
                           const pos = this.pos;
                           for (let i = 0; i < groupLabels.length; i++) {
                              const g = groupLabels[i];
                              const center = (g.from + g.to) / 2;
                              if (Math.abs(center - pos) < 0.6) return g.name;
                           }
                           return '';
                        },
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontWeight: '400',
                           fontSize: '11px'
                        },
                        y: 8
                     },
                     tickLength: 0,
                     lineWidth: 0,
                     labelsAlign: 'center'
                  }],
                  yAxis: {
                     allowDecimals: false,
                     title: {
                        text: null
                     }
                  },
                  tooltip: {
                     pointFormat: '<b>{point.y}</b> defects',
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  plotOptions: {
                     column: {
                        grouping: false,
                        pointPadding: 0.1,
                        borderWidth: 0
                     }
                  },
                  series: [{
                     name: 'Defect Count',
                     data: data,
                     color: '#005f73'
                  }],
                  legend: {
                     enabled: false
                  },
                  credits: {
                     enabled: false
                  }
               });

               resolve('Chart loaded successfully');
            },
            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   };

   const fetch_sub_defect_details_breakdown_chart = () => {
      return new Promise((resolve, reject) => {
         const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedDefectCategory.length !== 1) {
            $('#sub_defect_details_breakdown_chart').html('<p class="text-info text-center text-sm">Please select exactly one defect category to view its defect details breakdown.</p>');
            reject('Select exactly one defect category');
            return;
         }

         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();
         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#sub_defect_details_breakdown_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters: years or months');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_sub_defect_details_breakdown_chart',
               defect_category: selectedDefectCategory[0],
               years: selectedYears,
               months: selectedMonths
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#sub_defect_details_breakdown_chart').html('<p class="text-muted text-center">No data found for this defect category.</p>');
                  resolve('No data');
                  return;
               }

               let categories = [];
               let groupLabels = [];
               let detailMap = {};

               const alternatingColors = [
                  "#0a9396", // teal
                  "#ee9b00", // amber
                  "#001219", // dark navy
                  "#ca6702", // orange
                  "#94d2bd", // light teal
                  "#ae2012", // red
                  "#e9d8a6", // light sand
                  "#bb3e03", // dark orange
                  "#005f73", // teal-blue
                  "#9b2226" // dark red
               ];

               const monthNamesMap = {
                  "JAN": "JANUARY",
                  "FEB": "FEBRUARY",
                  "MAR": "MARCH",
                  "APR": "APRIL",
                  "MAY": "MAY",
                  "JUN": "JUNE",
                  "JUL": "JULY",
                  "AUG": "AUGUST",
                  "SEP": "SEPTEMBER",
                  "OCT": "OCTOBER",
                  "NOV": "NOVEMBER",
                  "DEC": "DECEMBER"
               };

               // Build category (weeks), group labels (months), and series data (defect details)
               response.forEach(monthData => {
                  const startIndex = categories.length;

                  // Add week labels for each month
                  monthData.weeks.forEach(week => {
                     categories.push(week.week_label);

                     week.details.forEach(item => {
                        if (!detailMap[item.defect_details]) detailMap[item.defect_details] = [];
                        detailMap[item.defect_details].push(parseInt(item.count));
                     });

                     // Fill 0 for missing series
                     for (let key in detailMap) {
                        if (!week.details.find(d => d.defect_details === key)) {
                           detailMap[key].push(0);
                        }
                     }
                  });

                  const endIndex = categories.length - 1;

                  groupLabels.push({
                     name: monthNamesMap[monthData.month.toUpperCase()] || monthData.month.toUpperCase(),
                     from: startIndex,
                     to: endIndex
                  });
               });

               const tickPositions = groupLabels.map(g => (g.from + g.to) / 2);

               const series = Object.keys(detailMap).map((detail, i) => ({
                  name: detail,
                  data: detailMap[detail],
                  color: alternatingColors[i % alternatingColors.length]
               }));

               $('#sub_defect_details_breakdown_chart').css('min-height', '290px');

               Highcharts.chart('sub_defect_details_breakdown_chart', {
                  chart: {
                     type: 'column',
                     backgroundColor: '#fff'
                  },
                  colors: alternatingColors,
                  title: {
                     text: `${selectedDefectCategory[0]} - Weekly Defect Details Breakdown`,
                     align: 'left',
                     style: {
                        fontSize: '13px',
                        fontWeight: '600',
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  xAxis: [{
                     categories: categories,
                     tickLength: 0,
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        },
                        y: 20
                     },
                     crosshair: true,
                     plotLines: groupLabels.slice(1).map(g => ({
                        color: '#DDD',
                        width: 1,
                        value: g.from - 0.5,
                        zIndex: 5
                     }))
                  }, {
                     linkedTo: 0,
                     opposite: false,
                     tickPositions: tickPositions,
                     labels: {
                        formatter: function() {
                           const pos = this.pos;
                           for (let i = 0; i < groupLabels.length; i++) {
                              const g = groupLabels[i];
                              if (Math.abs((g.from + g.to) / 2 - pos) < 0.6) return g.name;
                           }
                           return '';
                        },
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontWeight: '400',
                           fontSize: '11px'
                        },
                        y: 8
                     },
                     tickLength: 0,
                     lineWidth: 0
                  }],
                  yAxis: {
                     allowDecimals: false,
                     title: {
                        text: null,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     useHTML: true,
                     formatter: function() {
                        let tooltip = `${this.point.category}<br/>`;
                        this.points.forEach(p => {
                           tooltip += `<span style="color:${p.color}">●</span> ${p.series.name}: <b>${p.y}</b><br/>`;
                        });
                        return tooltip;
                     },
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  plotOptions: {
                     column: {
                        stacking: null,
                        borderWidth: 0
                     }
                  },
                  series: series,
                  legend: {
                     enabled: true,
                     itemStyle: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px',
                        fontWeight: '400'
                     },
                     symbolHeight: 12,
                     symbolWidth: 12,
                     symbolRadius: 0,
                     squareSymbol: true
                  },
                  credits: {
                     enabled: false
                  }
               });

               resolve('Chart loaded successfully');
            },
            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   };

   const fetch_sequence_no_breakdown_chart = () => {
      return new Promise((resolve, reject) => {
         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#sequence_no_breakdown_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters: years or months');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_sequence_no_breakdown_chart',
               years: selectedYears,
               months: selectedMonths,
               defect_category: selectedDefectCategory
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#sequence_no_breakdown_chart').html('<p class="text-muted text-center">No data found for the selected filters.</p>');
                  resolve('No data');
                  return;
               }

               // Ensure response is an array
               if (!Array.isArray(response)) response = [response];

               const categories = response.map(item => item.process_sequence.trim());
               const data = response.map(item => parseInt(item.total_records));

               Highcharts.chart('sequence_no_breakdown_chart', {
                  chart: {
                     type: 'bar',
                     backgroundColor: '#fff',
                     height: '290px'
                  },
                  title: {
                     text: 'Top Sequence No. by Process',
                     align: 'left',
                     style: {
                        fontSize: '13px',
                        fontWeight: '600',
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  xAxis: {
                     categories: categories,
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
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        overflow: 'justify',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     },
                     formatter: function() {
                        return `<b>${this.x}</b><br/>Total Records: <b>${this.y}</b>`;
                     }
                  },
                  plotOptions: {
                     bar: {
                        borderWidth: 0,
                        colorByPoint: false,
                        dataLabels: {
                           enabled: false
                        }
                     }
                  },
                  series: [{
                     name: 'Records',
                     data: data,
                     color: '#1e6091'
                  }],
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  }
               });

               resolve('Chart loaded successfully');
            },
            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   };

   const fetch_connector_no_breakdown_chart = () => {
      return new Promise((resolve, reject) => {
         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#connector_no_breakdown_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters: years or months');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_connector_no_breakdown_chart',
               years: selectedYears,
               months: selectedMonths,
               defect_category: selectedDefectCategory
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#connector_no_breakdown_chart').html('<p class="text-muted text-center">No data found for the selected filters.</p>');
                  resolve('No data');
                  return;
               }

               // Ensure response is an array
               if (!Array.isArray(response)) response = [response];

               const categories = response.map(item => item.process_connector.trim());
               const data = response.map(item => parseInt(item.total_records));

               Highcharts.chart('connector_no_breakdown_chart', {
                  chart: {
                     type: 'bar',
                     backgroundColor: '#fff',
                     height: '290px'
                  },
                  title: {
                     text: 'Top Connector No. by Process',
                     align: 'left',
                     style: {
                        fontSize: '13px',
                        fontWeight: '600',
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  xAxis: {
                     categories: categories,
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
                  yAxis: {
                     min: 0,
                     title: {
                        text: null
                     },
                     labels: {
                        overflow: 'justify',
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     },
                     formatter: function() {
                        return `<b>${this.x}</b><br/>Total Records: <b>${this.y}</b>`;
                     }
                  },
                  plotOptions: {
                     bar: {
                        borderWidth: 0,
                        colorByPoint: false,
                        dataLabels: {
                           enabled: false
                        }
                     }
                  },
                  series: [{
                     name: 'Records',
                     data: data,
                     color: '#1e6091'
                  }],
                  credits: {
                     enabled: false
                  },
                  legend: {
                     enabled: false
                  }
               });

               resolve('Chart loaded successfully');
            },
            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   }

   const fetch_line_category_month_week_chart = () => {
      return new Promise((resolve, reject) => {
         const selectedDefectCategory = $('.defect-checkbox:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedYears = $('.year-check:checked').map(function() {
            return $(this).val();
         }).get();

         const selectedMonths = $('.month-check:checked').map(function() {
            return $(this).val();
         }).get();

         if (selectedYears.length === 0 || selectedMonths.length === 0) {
            $('#line_category_month_week_chart').html('<p class="text-muted text-center">Please select at least one year and one month.</p>');
            reject('Missing filters: years or months');
            return;
         }

         $.ajax({
            url: 'process/weekly_monitoring_p.php',
            type: 'POST',
            dataType: 'json',
            data: {
               method: 'fetch_line_category_month_week_chart',
               defect_category: selectedDefectCategory,
               years: selectedYears,
               months: selectedMonths
            },
            success: function(response) {
               if (!response || response.length === 0) {
                  $('#line_category_month_week_chart').html('<p class="text-muted text-center">No data found for the selected filters.</p>');
                  resolve('No data');
                  return;
               }

               let categories = [];
               let groupLabels = [];
               let lineCategoryMap = {};

               const alternatingColors = [
                  "#34a0a4", // blue
                  "#b5e48c", // green
                  "#168aad", // blue
                  "#76c893" // green
               ];

               const monthNamesMap = {
                  "JAN": "JANUARY",
                  "FEB": "FEBRUARY",
                  "MAR": "MARCH",
                  "APR": "APRIL",
                  "MAY": "MAY",
                  "JUN": "JUNE",
                  "JUL": "JULY",
                  "AUG": "AUGUST",
                  "SEP": "SEPTEMBER",
                  "OCT": "OCTOBER",
                  "NOV": "NOVEMBER",
                  "DEC": "DECEMBER"
               };

               response.forEach(monthData => {
                  const startIndex = categories.length;

                  // Add week's labels to x-axis
                  monthData.weeks.forEach(week => categories.push(week.week_label));

                  // Register all line categories from this month into the map if new
                  monthData.line_categories.forEach(lc => {
                     if (!lineCategoryMap[lc.line_category]) {
                        // Fill previous months with zeros to align correctly
                        const totalPrevWeeks = categories.length - monthData.weeks.length;
                        lineCategoryMap[lc.line_category] = Array(totalPrevWeeks).fill(0);
                     }
                  });

                  // Determine how many weeks this month has
                  const totalWeeks = monthData.weeks.length;

                  // Now append data correctly for all known line categories
                  Object.keys(lineCategoryMap).forEach(lcName => {
                     const categoryData = monthData.line_categories.find(l => l.line_category === lcName);

                     if (categoryData) {
                        categoryData.weekly_records.forEach(wr => {
                           lineCategoryMap[lcName].push(parseInt(wr.total_records));
                        });

                        // pad if fewer weeks
                        if (categoryData.weekly_records.length < totalWeeks) {
                           const diff = totalWeeks - categoryData.weekly_records.length;
                           for (let i = 0; i < diff; i++) {
                              lineCategoryMap[lcName].push(0);
                           }
                        }
                     } else {
                        // Category didn’t appear in this month — pad zeros for this month’s weeks
                        for (let i = 0; i < totalWeeks; i++) {
                           lineCategoryMap[lcName].push(0);
                        }
                     }
                  });

                  const endIndex = categories.length - 1;
                  groupLabels.push({
                     name: monthNamesMap[monthData.month.toUpperCase()] || monthData.month.toUpperCase(),
                     from: startIndex,
                     to: endIndex
                  });
               });

               const tickPositions = groupLabels.map(g => (g.from + g.to) / 2);

               const series = Object.keys(lineCategoryMap).map((lc, i) => ({
                  name: lc,
                  data: lineCategoryMap[lc],
                  color: alternatingColors[i % alternatingColors.length]
               }));

               $('#line_category_month_week_chart').css('min-height', '290px');

               Highcharts.chart('line_category_month_week_chart', {
                  chart: {
                     type: 'column',
                     backgroundColor: '#fff'
                  },
                  colors: alternatingColors,
                  title: {
                     text: 'Weekly Breakdown per Line Category',
                     align: 'left',
                     style: {
                        fontSize: '13px',
                        fontWeight: '600',
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  xAxis: [{
                     categories: categories,
                     tickLength: 0,
                     labels: {
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        },
                        y: 20
                     },
                     crosshair: true,
                     plotLines: groupLabels.slice(1).map(g => ({
                        color: '#DDD',
                        width: 1,
                        value: g.from - 0.5,
                        zIndex: 5
                     }))
                  }, {
                     linkedTo: 0,
                     opposite: false,
                     tickPositions: tickPositions,
                     labels: {
                        formatter: function() {
                           const pos = this.pos;
                           for (let i = 0; i < groupLabels.length; i++) {
                              const g = groupLabels[i];
                              if (Math.abs((g.from + g.to) / 2 - pos) < 0.6) return g.name;
                           }
                           return '';
                        },
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontWeight: '400',
                           fontSize: '11px'
                        },
                        y: 8
                     },
                     tickLength: 0,
                     lineWidth: 0
                  }],
                  yAxis: {
                     allowDecimals: false,
                     title: {
                        text: null,
                        style: {
                           fontFamily: 'Poppins, sans-serif',
                           fontSize: '11px'
                        }
                     }
                  },
                  tooltip: {
                     shared: true,
                     useHTML: true,
                     formatter: function() {
                        let tooltip = `${this.point.category}<br/>`;
                        this.points.forEach(p => tooltip += `<span style="color:${p.color}">●</span> ${p.series.name}: <b>${p.y}</b><br/>`);
                        return tooltip;
                     },
                     style: {
                        fontFamily: 'Poppins, sans-serif'
                     }
                  },
                  plotOptions: {
                     column: {
                        stacking: null,
                        borderWidth: 0
                     }
                  },
                  series: series,
                  legend: {
                     enabled: true,
                     itemStyle: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px',
                        fontWeight: '400'
                     },
                     symbolHeight: 12,
                     symbolWidth: 12,
                     symbolRadius: 0,
                     squareSymbol: true,
                     labelFormatter: function() {
                        return `${this.name}`;
                     }
                  },
                  credits: {
                     enabled: false
                  }
               });

               resolve('Chart loaded successfully');
            },
            error: function(xhr, status, error) {
               console.error('AJAX Error:', error);
               reject(error);
            }
         });
      });
   };
</script>