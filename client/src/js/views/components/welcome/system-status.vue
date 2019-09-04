<template>
    <div class="content">
        <h1>System Status</h1>
        <ul>
            <li v-for="(system, index) in systems" v-bind:key="index">
                <div>
                    <h3>{{system.name}}</h3>
                    <p>{{system.description}}</p>
                    <p v-bind:class="[{'ok': system.status=='OK'}, {'warning': system.status=='WARNING'}]"> {{system.status}} <i class="fa" v-bind:class="[{'fa-check': system.status=='OK'}, {'fa-exclamation-triangle': system.status=='WARNING'}]"></i></p>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
                systems: [
                    {name: "ISPyB", description: "ISPyB database cluster", status: "OK"},
                    {name: "Zocalo", description: "Processing messaging service", status: "OK"},
                    {name: "CAS", description: "Authentication Service", status: "WARNING"},
                    {name: "GPFS", description: "Filesystem", status: "WARNING"},
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
.content h3 {
    color: darkslategrey;
    font-size: 1.5em;
    font-weight: bold;
}
p.ok {
    color: green;
}
p.warning {
    color: orange;
}
</style>
