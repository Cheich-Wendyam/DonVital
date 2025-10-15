document.addEventListener('DOMContentLoaded', function () {
    try {
        // Annonces et dons par mois
        let chart1 = new ApexCharts(document.querySelector("#annoncesDonsChart"), {
            chart: { type: 'bar', height: 350 },
            series: [
                { name: 'Annonces', data: JSON.parse(document.getElementById('annoncesDonsChart').getAttribute('annonces_data')) },
                { name: 'Dons', data: JSON.parse(document.getElementById('annoncesDonsChart').getAttribute('dons_data')) }
            ],
            xaxis: {
                categories: JSON.parse(document.getElementById('annoncesDonsChart').getAttribute('mois'))
            }
        });
        chart1.render();

        // Annonces actives/inactives/fermées
        let chart2 = new ApexCharts(document.querySelector("#annoncesActivesChart"), {
            chart: { type: 'pie', height: 350 },
            series: [
                JSON.parse(document.getElementById('annoncesActivesChart').getAttribute('active_data')),
                JSON.parse(document.getElementById('annoncesActivesChart').getAttribute('inactive_data')),
                JSON.parse(document.getElementById('annoncesActivesChart').getAttribute('ferme_data'))
            ],
            labels: ['Actives', 'Inactives', 'Fermées']
        });
        chart2.render();

        // Radial Semaine
        let radialChart1 = new ApexCharts(document.querySelector("#apex-radialbar-2"), {
            chart: { type: 'radialBar', height: 350 },
            series: [JSON.parse(document.getElementById('apex-radialbar-2').getAttribute('data_weekUsers'))],
            labels: ['Connexions cette semaine'],
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: { fontSize: '22px' },
                        value: { fontSize: '16px' }
                    }
                }
            }
        });
        radialChart1.render();

        // Radial Mois
        let radialChart2 = new ApexCharts(document.querySelector("#apex-radialbar-3"), {
            chart: { type: 'radialBar', height: 350 },
            series: [JSON.parse(document.getElementById('apex-radialbar-3').getAttribute('data_monthUsers'))],
            labels: ['Connexions ce mois'],
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: { fontSize: '22px' },
                        value: { fontSize: '16px' }
                    }
                }
            }
        });
        radialChart2.render();

        let campagnesChart = new ApexCharts(document.querySelector("#campagnesChart"), {
        chart: {
            type: 'donut',
            height: 350
        },
        series: [
            parseInt(document.getElementById('campagnesChart').getAttribute('en_cours')),
            parseInt(document.getElementById('campagnesChart').getAttribute('a_venir')),
            parseInt(document.getElementById('campagnesChart').getAttribute('terminees'))
        ],
        labels: ['En cours', 'À venir', 'Terminées'],
        colors: ['#28a745', '#ffc107', '#17a2b8'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    });
    campagnesChart.render();

    } catch (error) {
        console.error("Erreur lors de l'initialisation des graphiques :", error);
    }
}
);
setTimeout(() => {
    const loader = document.getElementById('dashboard-loader');
    if (loader) loader.style.display = 'none';
}, 800); // Attente d'animation + délai de sécurité

