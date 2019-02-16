<template>
    <div class="dropdown">
        <button class="btn nav-link text-light fa-lg" 
            id="dropdownMenuButton" 
            data-toggle="dropdown"
            aria-haspopup="true" 
            aria-expanded="false" 
            :disabled="!notifications.length">
            <i class="fas fa-bell"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <li v-for="notification in notifications" :key="notification.id">
                <a class="dropdown-item" 
                    :href="notification.data.link" 
                    v-text="notification.data.message"
                    @click="markAsRead(notification)"
                ></a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: false
            }
        },
        created() {
            axios.get(`/profiles/${window.App.user.name}/notifications`)
                .then(response => {
                    this.notifications = response.data;
                });
        },
        methods: {
            markAsRead(notification) {
                axios.delete(`/profiles/${window.App.user.name}/notifications/${notification.id}`)
                    .then(() => {
                        this.notifications = this.notifications.filter(n => n.id != notification.id);
                    });
            }
        }
    }
</script>

