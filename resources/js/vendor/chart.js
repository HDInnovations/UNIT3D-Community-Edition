// ChartJS
import { Chart, registerables } from 'chart.js';
import 'chartjs-adapter-date-fns';
Chart.register(...registerables);
window.Chart = Chart;
