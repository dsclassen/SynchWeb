
<template>

  <div class="notebook-table">
      
        <button @click="onNewNotebook">Add New Notebook</button>

        <br /><br />

        <table class="nb-table">
            <thead>
                <th>Index</th>
                <th>Description</th>
                <th>Last Updated</th>
                <th>Last Updated By</th>
                <th>Created</th>
                <th>Created By</th>
            </thead>
            <tbody>
                <tr v-for="(nb, index) in notebooks" :key="index" @click="onShowNotebook(index)">
                    <td>{{index}}</td>
                    <td>{{nb.TITLE}}</td>
                    <td>{{nb.MODIFIED}}</td>
                    <td>{{nb.MODIFIEDBY}}</td>
                    <td>{{nb.CREATED}}</td>
                    <td>{{nb.CREATEDBY}}</td>
                </tr>
            </tbody>
        </table>

  </div>
</template>

<script>
    export default {
        name: 'NotebookList',
    
        props: {
            notebooks: Array,
        },

        data: function(){
            return {

            }
        },

        methods: {
            onNewNotebook: function(){
                console.log("Show Notebook")
                this.$emit('add-notebook')
            },

            onShowNotebook: function(index){
                console.log('Show Notebook Number: ' + JSON.stringify(index))
                this.$emit('edit-notebook', index)
            }
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
</style>