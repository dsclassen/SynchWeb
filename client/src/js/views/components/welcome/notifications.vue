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
                    "Example notification 1",
                    "Example notification 2",
                    "Example notification 3",
                    "Example notification 4",
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
