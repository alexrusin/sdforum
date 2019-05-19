<template>
    <div>
        <h1>{{title}}</h1>
            <div v-show="validData"> 
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">  
                            <select class="form-control" v-model="time" @change="reRender">
                                <option :value="{type: 'DIGITAL_CURRENCY_DAILY', length: 7}">last 7 days</option>
                                <option :value="{type: 'DIGITAL_CURRENCY_DAILY', length: 30}">1 month</option>
                                <option :value="{type: 'DIGITAL_CURRENCY_DAILY', length: 90}">3 months</option>
                                <option :value="{type: 'DIGITAL_CURRENCY_WEEKLY', length: 26}">6 months</option>
                                <option :value="{type: 'DIGITAL_CURRENCY_WEEKLY', length: 52}">1 year</option>
                                <option :value="{type: 'DIGITAL_CURRENCY_MONTHLY', length: 24}">2 years</option>
                                <option :value="{type: 'DIGITAL_CURRENCY_MONTHLY', length: 36}">3 years</option>
                                <option :value="{type: 'DIGITAL_CURRENCY_MONTHLY', length: 60}">5 years</option>
                            </select>            
                        </div>
                    </div>
                </div>
                <canvas  width="800" height="600" ref="canvas"></canvas>
                <div class="legend" v-html="legend"></div>
            </div>
        </div>
</template>

<script>
    import StockChart from './StockChart.vue';
    export default StockChart.extend ({
    	props: ['currencySymbol', 'apiKey', 'requestOnload', 'market'],

        data() {
            return {
                stockFunction: 'DIGITAL_CURRENCY_DAILY',
                time: {type: 'DIGITAL_CURRENCY_DAILY', length: 90}
            };
        },

       computed: {
            url() {
                return `https://www.alphavantage.co/query?function=${this.time.type}&symbol=${this.currencySymbol}&market=${this.market}&apikey=${this.apiKey}`;
            }
       },

        methods: {

            processData(data) {

                this.symbol = data["Meta Data"]["2. Digital Currency Code"].toUpperCase();
                this.title = data["Meta Data"]["1. Information"] + " " + data["Meta Data"]["3. Digital Currency Name"] + " traded on " + data["Meta Data"]["4. Market Code"];
                this.stockFunction = this.time.type;

                switch(this.time.type) {
                    case 'DIGITAL_CURRENCY_DAILY':
                        this.timeSeries = data["Time Series (Digital Currency Daily)"];
                        break;
                    case 'DIGITAL_CURRENCY_WEEKLY':
                        this.timeSeries = data["Time Series (Digital Currency Weekly)"];
                        break;
                    case 'DIGITAL_CURRENCY_MONTHLY':
                        this.timeSeries = data["Time Series (Digital Currency Monthly)"];
                        break;
                    default:
                        case 'DIGITAL_CURRENCY_DAILY':
                        this.timeSeries = data["Time Series (Digital Currency Daily)"];
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
                    this.data.push(this.timeSeries[label]["4b. close (USD)"]);
                    count++;
                }

                this.labels = this.labels.reverse();
                this.data = this.data.reverse();
            },
        }

    });
</script>