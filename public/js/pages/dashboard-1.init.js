var options={chart:{height:279,type:"bar",toolbar:{show:!1}},plotOptions:{bar:{columnWidth:"30%",endingShape:"rounded"}},dataLabels:{enabled:!1},stroke:{width:2},colors:["#f0643b"],series:[{name:"Growth",data:[44,55,41,67,22,43,21,33,45,31,87,65]}],grid:{borderColor:"#f1f3fa",padding:{right:0,bottom:0,left:0}},xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],offsetX:0},yaxis:{title:{text:"Growth"}},fill:{type:"gradient",gradient:{shade:"light",type:"horizontal",shadeIntensity:.25,gradientToColors:void 0,inverseColors:!0,opacityFrom:.85,opacityTo:.85,stops:[50,0,100]}}};(chart=new ApexCharts(document.querySelector("#rotate-labels-column"),options)).render();options={chart:{height:480,type:"line",zoom:{enabled:!1},toolbar:{show:!1}},colors:["#f0643b","#56c2d6"],dataLabels:{enabled:!0},stroke:{width:[3,3],curve:"smooth"},series:[{name:"Previus Week",data:[32,42,42,62,52,75,62]},{name:"Current Week",data:[42,58,66,93,82,105,92]}],grid:{row:{colors:["transparent","transparent"],opacity:.2},borderColor:"#f1f3fa"},markers:{style:"inverted",size:6},xaxis:{categories:["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],title:{text:"Week 12 - March 18, 2019 to March 24, 2019"}},yaxis:{title:{text:"Sales Analytics"},min:5,max:120},legend:{position:"top",horizontalAlign:"right",floating:!0,offsetY:-25,offsetX:-5},responsive:[{breakpoint:600,options:{chart:{toolbar:{show:!1}},legend:{show:!1}}}]};(chart=new ApexCharts(document.querySelector("#apex-line-1"),options)).render();var chart;options={chart:{height:337,type:"radar",toolbar:{show:!1}},series:[{name:"Desktops",data:[80,50,30,40,100,20]},{name:"Tablets",data:[20,30,40,80,20,80]},{name:"Mobiles",data:[44,76,78,13,43,10]}],stroke:{width:0,curve:"smooth"},fill:{opacity:.4},markers:{size:0},legend:{show:!0,offsetY:-10},colors:["#5089de","#56c2d6","#f0643b"],labels:["2011","2012","2013","2014","2015","2016"]};(chart=new ApexCharts(document.querySelector("#radar-multiple-series"),options)).render(),$("#usa-map").vectorMap({map:"us_merc_en",backgroundColor:"transparent",regionStyle:{initial:{fill:"#f5f6f8"}},series:{regions:[{values:{"US-VA":"#a6d8d1","US-PA":"#a6d8d1","US-TN":"#a6d8d1","US-WY":"#a6d8d1","US-WA":"#a6d8d1","US-TX":"#a6d8d1"},attribute:"fill"}]}});

    // Graphique des annonces et dons par mois
    var options1 = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Annonces',
            data: $annoncesParMois
        }, {
            name: 'Dons',
            data: $donsParMois
        }],
        xaxis: {
            categories: $mois
        }
    };
    var chart1 = new ApexCharts(document.querySelector("#annoncesDonsChart"), options1);
    chart1.render();

    // Graphique des annonces actives vs inactives
    var options2 = {
        chart: {
            type: 'pie',
            height: 350
        },
        series: [ $annoncesActives ,  $annoncesInactives ],
        labels: ['Actives', 'Inactives']
    }
    var chart2 = new ApexCharts(document.querySelector("#annoncesActivesChart"), options2);
    chart2.render();

    // Radial Bar Chart 1
    var options3 = {
        chart: {
            height: 350,
            type: 'radialBar'
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 135,
                dataLabels: {
                    name: {
                        fontSize: "16px",
                        color: undefined,
                        offsetY: 120
                    },
                    value: {
                        offsetY: 76,
                        fontSize: "22px",
                        color: undefined,
                        formatter: function(val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        fill: {
            gradient: {
                enabled: true,
                shade: "dark",
                shadeIntensity: 0.15,
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 50, 65, 91]
            }
        },
        stroke: {
            dashArray: 4
        },
        colors: ["#414d5f"],
        series: [67],
        labels: ["Median Ratio"],
        responsive: [{
            breakpoint: 380,
            options: {
                chart: {
                    height: 280
                }
            }
        }]
    };
    var chart3 = new ApexCharts(document.querySelector("#apex-radialbar-2"), options3);
    chart3.render();

    // Radial Bar Chart 2
    var options4 = {
        chart: {
            height: 350,
            type: 'radialBar'
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 135,
                dataLabels: {
                    name: {
                        fontSize: "16px",
                        color: undefined,
                        offsetY: 120
                    },
                    value: {
                        offsetY: 76,
                        fontSize: "22px",
                        color: undefined,
                        formatter: function(val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        fill: {
            gradient: {
                enabled: true,
                shade: "dark",
                shadeIntensity: 0.15,
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 50, 65, 91]
            }
        },
        stroke: {
            dashArray: 4
        },
        colors: ["#ffbc00"],
        series: [85],
        labels: ["Completion Rate"],
        responsive: [{
            breakpoint: 380,
            options: {
                chart: {
                    height: 280
                }
            }
        }]
    };
    var chart4 = new ApexCharts(document.querySelector("#apex-radialbar-3"), options4);
    chart4.render();

