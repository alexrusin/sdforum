<template>
    <div>
        <h1>{{title}}</h1>
        <div v-show="validData">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">  
                        <select class="form-control" v-model="time" @change="reRender">
                            <option :value="{type: 'TIME_SERIES_DAILY_ADJUSTED', length: 7}">last 7 days</option>
                            <option :value="{type: 'TIME_SERIES_DAILY_ADJUSTED', length: 30}">1 month</option>
                            <option :value="{type: 'TIME_SERIES_DAILY_ADJUSTED', length: 90}">3 months</option>
                            <option :value="{type: 'TIME_SERIES_WEEKLY_ADJUSTED', length: 26}">6 months</option>
                            <option :value="{type: 'TIME_SERIES_WEEKLY_ADJUSTED', length: 52}">1 year</option>
                            <option :value="{type: 'TIME_SERIES_MONTHLY_ADJUSTED', length: 24}">2 years</option>
                            <option :value="{type: 'TIME_SERIES_MONTHLY_ADJUSTED', length: 36}">3 years</option>
                            <option :value="{type: 'TIME_SERIES_MONTHLY_ADJUSTED', length: 60}">5 years</option>
                            <option :value="{type: 'TIME_SERIES_MONTHLY_ADJUSTED', length: 120}">10 years</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <canvas  width="800" height="600" ref="canvas"></canvas>
                    <div class="legend" v-html="legend"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import outboundAxios from './outboundAxios';
    import ParentGraph from './ParentGraph.vue';
    export default ParentGraph.extend ({
    	props: ['stockSymbol', 'apiKey', 'requestOnload'],

        data() {
            return {
                validData: false,
                title: '',
                timeSeries: {},
                labels: [],
                data: [],
                symbol:'',
                stockFunction: 'TIME_SERIES_DAILY_ADJUSTED',
                time: {type: 'TIME_SERIES_DAILY_ADJUSTED', length: 90}
            };
        },

       computed: {
            url() {
                return `https://www.alphavantage.co/query?function=${this.time.type}&symbol=${this.stockSymbol}&apikey=${this.apiKey}`;
            }
       },

    	mounted() {
            if (this.requestOnload) {
                this.sendRequest();
            }
            
           
        }, 

        methods: {

            sendRequest() {
                outboundAxios.get(this.url)
                   .then((response) => {
                     const errorResponse = "Error Message"; 

                     if(response.data[errorResponse]) {
                        this.title = "There was an error retrieving data.";
                        this.validData = false;
                        return;
                     }
                     this.validData = true;
                     this.processData(response.data);
                     this.massageData();

                     this.render(this.displayData);


                   })
                   .catch((error) => {
                     console.log(error);
                   });
            },

            processData(data) {

                this.symbol = data["Meta Data"]["2. Symbol"].toUpperCase();
                this.title = data["Meta Data"]["1. Information"] + " for " + this.symbol;
                this.stockFunction = this.time.type;

                switch(this.time.type) {
                    case 'TIME_SERIES_DAILY_ADJUSTED':
                        this.timeSeries = data["Time Series (Daily)"];
                        break;
                    case 'TIME_SERIES_WEEKLY_ADJUSTED':
                        this.timeSeries = data["Weekly Adjusted Time Series"];
                        break;
                    case 'TIME_SERIES_MONTHLY_ADJUSTED':
                        this.timeSeries = data["Monthly Adjusted Time Series"];
                        break;
                    default:
                        case 'TIME_SERIES_DAILY_ADJUSTED':
                        this.timeSeries = data["Time Series (Daily)"];
                }
            },

            massageData() {
                let count = 0;
                this.labels = [];
                this.data = [];
                for (let label in this.timeSeries) {
                    if (count > this.time.length-1) {
                        break;
                    }
                    this.labels.push(label);
                    this.data.push(this.timeSeries[label]["5. adjusted close"]);
                    count++;
                }

                this.labels = this.labels.reverse();
                this.data = this.data.reverse();
            },

            reRender() {

                if (this.stockFunction != this.time.type) {
                    this.myChart.destroy();
                    this.sendRequest();
                } else {
                    this.myChart.destroy();
                    this.massageData();
                    this.render(this.displayData);

                }
            }
        }

    });
</script>