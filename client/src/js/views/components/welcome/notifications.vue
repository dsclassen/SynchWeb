<template>
    <div class="content">
        <h1>Recent Updates</h1>
        <ul>
            <li v-for="(notification, index) in notifications" v-bind:key="index">{{notification}}</li>
        </ul>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
                notifications: [
                    "This is a notification",
                    "Another notification",
                    "Another feature has been added",
                    "Vue.js is amazing",
                ]
            }
        }
    }
</script>

<style scoped>
.content {
    text-align: center;
}
.content h1 {
    color: darkslategrey;
    font-size: 2em;
}
</style>
