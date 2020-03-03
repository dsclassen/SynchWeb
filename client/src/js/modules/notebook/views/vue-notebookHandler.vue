<template>
    <div>
        <section v-if="showNotebooks">
            <NotebookList
                v-bind:notebooks="notebooks"
                v-on:add-notebook="addNote"
                v-on:edit-notebook="editNote">
            </NotebookList>
        </section>
        <section v-else>
            <Notebook
                v-bind:initialNotebook="currentNotebook"
                v-bind:notebookIndex="notebookIndex"
                v-on:update-notebook="onSaveNotebook"
                v-on:cancel="onCancelNotebook"
                v-on:delete-notebook="onDeleteNotebook">
            </Notebook>
        </section>
    </div>
</template>

<script>
    import Notebook from './vue-notebook.vue'
    import NotebookList from './vue-notebook-list.vue'
    import Backbone from 'backbone'
    
    export default {
        name: 'NotebookHandler',
        props: {

        },

        data: function(){
            return {
                notebookContent: '',
                notebookTitle: '',
                notebookIndex: 0,

                showNotebooks: true,

                /*columns: [
                    {
                        field: 'id',
                        label: 'ID',
                        width: '40',
                        numeric: true,
                        centered: true
                    },
                    {
                        field: 'title',
                        label: 'Title',
                        centered: true
                    },                {
                        field: 'date',
                        label: 'Date',
                        centered: true
                    },
                ],*/

                store: [],
            }
        },

        components: {
            Notebook,
            NotebookList,
        },

        mounted:function(){
            console.log('vue-notebookHandler mounted')
            this.getNotebooks()
        },

        computed:{
            notebooks(){
                return this.store
            },

            currentNotebook(){
                return {title: this.notebookTitle, text: this.notebookContent, index: this.notebookIndex}
            }
        },

        methods: {
            getNotebooks: function(){
                let self = this
                
                Backbone.ajax({
                    url: app.apiurl + '/notebook',
                    method: 'get',
                    data: {
                        visit: app.prop+'-'+app.visit
                    },
                    success: function(response){
                        self.store = response;
                        console.log('Got ' + response.length + ' notebooks for '+app.prop+'-'+app.visit)
                    },
                    error: function(response){
                        let responseObj = JSON.parse(response.responseText)
                        let alertMessage = "Failed to get notebooks"
                        if('message' in responseObj)
                            alertMessage = alertMessage + ': ' + responseObj.message

                        app.alert({message: alertMessage})
                        console.log('Failed to get notebooks...')
                    }
                })
            },

            addNote: function(){
                // Creating new content so set values to empty
                this.notebookContent = '<h1 id="masterTitle">Title</h1>'
                this.notebookTitle = ''
                // This will be a new notebook - get next index value
                this.notebookIndex = this.store.length
                // Finally show the editing panel...
                this.showNotebooks = false
            },

            editNote: function(index){
                console.log("Edit notebook index = " + index)
        
                if (index <= this.store.length) {
                    this.notebookContent = this.store[index].CONTENT
                    this.notebookTitle = this.store[index].TITLE
                    //console.log("Editing Content: " + this.notebookContent)
                    console.log("Editing Title: " + this.notebookTitle)
                    this.notebookIndex = index
                }
                // Now make the editor pane visible...
                this.showNotebooks = false
            },

            onSaveNotebook: function(content){
                let notebookID = null
                let method = 'put'

                if(content.index <= (this.store.length-1)){
                    notebookID = this.store[content.index].NOTEBOOKID
                    method = 'post'
                }

                let self = this
                
                Backbone.ajax({
                    url: app.apiurl + '/notebook',
                    method: 'post',
                    data: {
                        visit: app.prop+'-'+app.visit,
                        user: app.user,
                        nid: notebookID,
                        title: content.title,
                        content: content.text,
                        _METHOD: method
                    },
                    success: function(response){
                        self.getNotebooks()
                        app.alert({className: 'message, notify', message: 'Notebook Saved'})
                        console.log('Successfully added or saved notebook')
                    },
                    error: function(response){
                        let responseObj = JSON.parse(response.responseText)
                        let alertMessage = 'Failed to save notebook'
                        if('message' in responseObj)
                            alertMessage = alertMessage + ': ' + responseObj.message

                        app.alert({message: alertMessage})
                        console.log("Failed to save notebook")
                    }
                })

                this.showNotebooks = true
                this.notebookContent = ''
                this.noteBookTitle = ''
            },

            onCancelNotebook: function(){
                this.showNotebooks = true
                this.notebookContent = ''
                this.notebookTitle = ''
            },

            onDeleteNotebook: function(index){
                let self = this

                Backbone.ajax({
                    url: app.apiurl + '/notebook',
                    method: 'post',
                    data:{
                        visit: app.prop+'-'+app.visit,
                        nid: self.store[index].NOTEBOOKID,
                        _METHOD:'delete' 
                    },
                    success: function(response){
                        self.getNotebooks()
                        app.alert({className: 'message, notify', message:'Notebook Deleted'})
                        console.log('Notebook Deleted')
                    },
                    error: function(response){
                        let responseObj = JSON.parse(response.responseText)
                        let alertMessage = 'Failed to delete notebook'
                        if('message' in responseObj)
                            alertMessage = alertMessage + ': ' + responseObj.message

                        app.alert({message: alertMessage})
                        console.log('Failed to delete notebook')
                    }
                })

                //this.store[index] = '';
                //this.notebookContent = ''
                //this.notebookTitle = ''
                this.showNotebooks = true;
            }
        }
    }
</script>