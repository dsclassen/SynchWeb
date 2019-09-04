<template>
    <div class="welcome">
        <h1>SynchWeb Landing Page</h1>
        <p>{{message}}</p>
        <ul>
            <li><notifications></notifications></li>
            <li><system-status></system-status></li>
        </ul>
    </div>
</template>

<script>
import Notifications from 'views/components/welcome/notifications.vue'
import SystemStatus from 'views/components/welcome/system-status.vue'

export default {
    name: "Welcome Page",
    components: {
        "notifications": Notifications,
        "system-status": SystemStatus
    },
    data: function() {
        return {
            message: "SynchWeb is the Laboratory Information Management System used at Diamond Light Source. It provides a user interface to the ISPyB database"
        }
    }
}
</script>

<style scoped>
.welcome {
    text-align: center;
    font-size: 16px;
    font-family: 'Maven Pro';
}
h1 {
    font-size: 3em;  
}
</style>