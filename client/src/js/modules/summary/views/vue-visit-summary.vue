<template>
    <div>
        <h1>This visit has {{dcgcount}} Data Collection Groups with {{dccount}} Data Collections and {{pjcount}} Processing Jobs (auto processing)</h1>

        <br/><br/>

        <h1>This visit has {{dewarcount}} Dewars</h1>
        <br/>
        <table class="nb-table">
            <thead>
                <th>Index</th>
                <th>Dewar Name</th>
                <th>Location</th>
                <th>Updated</th>
            </thead>
            <tbody>
                <tr v-for="(dewar, index) in dewars" :key="index" @click="onShowDewar(index)">
                    <td>{{index}}</td>
                    <td>{{dewar.CODE}}
                    <td>{{dewar.STORAGELOCATION}}</td>
                    <td>{{dewar.ARRIVALDATE}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import Backbone from 'backbone'

    export default {
        name: 'VisitSummary',

        data: function(){
            return {
                dccount: 0,
                dcgcount: 0,
                pjcount: 0,
                dewars: Array,
                dewarcount: 0,
            }
        },

        mounted: function(){
            console.log('vue-visit-summary.vue mounted!')
            // get dewar info
            // get dc info
            // get autoprocessing/reprocessing info
            // maybe make all three into separate components and reuse as cards?

            this.getDCSummary()
            this.getDewarSummary()
        },

        methods: {
            getDCSummary: function(){
                let self = this
                console.log('getting DC Summary...')
                Backbone.ajax({
                    url: app.apiurl + '/dc/summary',
                    method: 'get',
                    data: {
                        //visit: app.prop+'-'+app.visit
                        visit: app.prop+'-4'
                    },
                    success: function(response){
                        
                        //console.log('Got ' + response.length + ' dcs for '+app.prop+'-'+app.visit)
                        console.log(response.DCS)
                        self.dcgcount = response[0].DCGS
                        self.dccount = response[0].DCS
                        self.pjcount = response[0].APS
                    },
                    error: function(response){
                        let responseObj = JSON.parse(response.responseText)
                        let alertMessage = "Failed to get dcs "
                        if('message' in responseObj)
                            alertMessage = alertMessage + ': ' + responseObj.message
                        app.alert({message: alertMessage})
                        console.log('Failed to get dcs...')
                    }
                })
            },

            getDewarSummary: function(){
                let self = this
                console.log('getting Dewar Summary...')
                Backbone.ajax({
                    url: app.apiurl + '/shipment/dewars/summary',
                    method: 'get',
                    data: {
                        //visit: app.prop+'-'+app.visit
                        visit: app.prop+'-4'
                    },
                    success: function(response){
                        
                        console.log('Got ' + response.length + ' dewars for '+app.prop+'-'+app.visit)
                        self.dewars = response
                        self.dewarcount = response.length
                    },
                    error: function(response){
                        let responseObj = JSON.parse(response.responseText)
                        let alertMessage = "Failed to get dewars "
                        if('message' in responseObj)
                            alertMessage = alertMessage + ': ' + responseObj.message
                        app.alert({message: alertMessage})
                        console.log('Failed to get dewars...')
                    }
                })
            },


            onShowDataCollection: function(index){
                console.log('onShowDataCollection() called')
                app.navigate('/dc/')
            },
        }
    }
</script>

<style>
    .nb-table {
        width: 100%;
        border-collapse: collapse;
    }
    .nb-table th {
        border-bottom:2px solid black;
        font-size: large;
        font-weight: bold;
    }
    .nb-table td {
        text-align: center;
        font-size: large;
        padding-top: 3px;
        padding-bottom: 3px;
    }
    .nb-table tr:hover td {
        background-color:darkgray;
    }

    h1 {
        font-size: large;
    }
</style>