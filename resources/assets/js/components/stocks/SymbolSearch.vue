<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="form-inline">
                     <div class="form-group">
                        <input class="form-control" type="text" placeholder="Symbol search..." v-model="symbol">
                        <button class="btn btn-primary" type="button" @click="findSymbol">Search</button>
                    </div>
                </div>
            </div>
        </div>
      
       <stock-chart :stock-symbol="symbol" api-key="YPSEZZ91IMYTHW1Z" :request-onload="false" ref="stockChart"></stock-chart> 
    </div>
</template>

<script>
    export default {

        data() {
            return {
                symbol:""
            };
        },

        methods: {
            findSymbol() {
                this.$refs.stockChart.sendRequest();
            }
        }
    }
</script>