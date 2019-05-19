<template>
	<div>
		<h1>{{title}}</h1>
    	<canvas  width="800" height="600" ref="canvas"></canvas>
    	<div class="legend" v-html="legend"></div>

	</div>
</template>
<script>
	import Vue from 'vue';
	import Chart from 'chart.js';
	export default Vue.extend({
		data() {
			return {
				legend: '',
				myChart: '',
			};
		},

		computed: {
			displayData() {
				return {
                         type: 'line',
                         data: {
                             labels: this.labels,
                             datasets: [
                                 {
                                 label: this.symbol,
                                 data: this.data,
                                    backgroundColor: [
                                       'rgba(54, 162, 235, 0.2)' 
                                         
                                         
                                     ],
                                     borderColor: [
                                         'rgba(54, 162, 235, 1)',
                                        
                                        
                                    ],
                                    borderWidth: 1
                                 }
                             ]
                         },
                         options: {
                             legend:false,
                             responsive: false
                         }
                     };
			}
		},

		methods: {
			render(data) {
				this.myChart = new Chart(this.$refs.canvas, data);
	            this.legend = this.myChart.generateLegend();
			}
		}

	});
</script>

<style>
    .legend span {
        width: 10px;
        height: 10px;
        display: inline-block;
        margin-right: 10px;
    }

    h1 {
    	font-size: 1.5em;
    }
</style>