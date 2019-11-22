<template>
    <div id="editors">

        <b-field label="Title" horizontal>
            <b-input :value="notebookTitle" v-model="notebookTitle"></b-input>
        </b-field>

        <b-field label="Notebook" horizontal>
            <editor
                ref="editor"
                id="tinymceEditor"
                v-model="notebookContent"
                api-key="" 
                :init="tinymce_config"
                :key="editorKey"
            >
            </editor>
        </b-field>

        <br />

        <b-field label="File Options" horizontal>
            <button v-on:click="onSaveNote">Save</button>
            <button v-on:click="onDeleteNote">Delete</button>
            <button v-on:click="onCancel">Cancel</button>
        </b-field>
        
    </div>
</template>

<script>
    import Editor from '@tinymce/tinymce-vue'

    const config = {
        min_height: 500,
        plugins: 'wordcount image autoresize lists fullscreen preview paste',
        toolbar: 'preview fullscreen | undo redo | fontselect fontsizeselect formatselect forecolor | bold italic strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist',
        file_picker_types: 'image',
        paste_data_images: true,

        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
        
            /*
                Note: In modern browsers input[type='file'] is functional without
                even adding it to the DOM, but that might not be the case in some older
                or quirky browsers like IE, so you might want to add it to the DOM
                just in case, and visually hide it. And do not forget do remove it
                once you do not need it anymore.
            */
        
            input.onchange = function () {
                var file = this.files[0];
        
                var reader = new FileReader();
                reader.onload = function () {
                /*
                    Note: Now we need to register the blob in TinyMCEs image blob
                    registry. In the next release this part hopefully won't be
                    necessary, as we are looking to handle it internally.
                */
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);
        
                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };
        
            input.click();
        }
    };

    export default {
        name: 'Notebook',
        props: {
            initialNotebook: Object,
            notebookIndex: Number,
        },

        components: {
            'editor': Editor,
        },

        created: function(){
            console.log('vue-notebook.vue created');
            var self = this
            this.tinymce_config.save_onsavecallback = function(){
                self.onSaveNote()
                console.log('Saved')
            }
        },

        data: function(){
            return {
                notebookContent: '',
                notebookTitle: '',
                editing: false,
                tinymce_config: config,
                editorKey: 0,
            }
        },

        mounted: function(){
            if(this.$refs.editor.$el.style.visibility != 'hidden'){
                console.log('rerendering editor...')
                this.forceRerender()
            }
            
            console.log("Mounted Editor: " + JSON.stringify(this.$props.initialNotebook))
            this.notebookContent = this.$props.initialNotebook.text
            this.notebookTitle = this.$props.initialNotebook.title
            this.editing = true
        },

        methods: {
            onSaveNote: function(){
                let body = new DOMParser().parseFromString(this.notebookContent, 'text/html').documentElement.children[1]
                
                if(body.children.length > 0){
                    if(body.firstChild.children.length == 0 && body.firstChild.innerHTML && body.firstChild.innerHTML.indexOf('&nbsp;') == -1){
                        this.notebookTitle = body.firstChild.innerHTML
                        let payload = {text: this.notebookContent, index: this.$props.notebookIndex, title: this.notebookTitle}
                        this.$emit('update-notebook', payload)
                        this.editing = false
                    } else {
                        app.alert({message: "Please add a title at the top of the document"})
                    }
                } else {
                    app.alert({message: "Please don't save empty notebooks"})
                }
            },

            onDeleteNote: function(){
                var confirm = window.confirm('Are you sure you want to delete this note?')
                if(confirm){
                    this.editing = false;
                    this.$emit('delete-notebook', this.$props.notebookIndex)
                }
            },

            onCancel: function(){
                this.editing = false;
                this.$emit('cancel')
            },

            /**
             * tinymce vue component would not pull in its required javascript when returning to this page, 
             * and therefore would not display the editor until a full page refresh.
             * By adding a key to the editor then changing it's value this forces it to reload (this works on all vue components).
             * Sadly doing that causes a new problem on first page load (or full page refresh) where the v-model binding breaks.
             * I suspect this is down to timing in some way where the component reload doesn't update the bind, but no proof of this.
             * The solution is to add a reference to the editor which can be accessed from mounted() via $refs to check the CSS within 
             * the editors $el variable. Works without setTimeout() :D
             */
            forceRerender: function(){
                this.editorKey+=1;
            },
        },
    }
</script>


