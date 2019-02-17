<template>
    <div class="dropdown">
        <button class="btn nav-link text-light fa-lg" 
            id="dropdownMenuButton" 
            data-toggle="dropdown"
            aria-haspopup="true" 
            aria-expanded="false" 
            :disabled="!notifications.length">
            <i class="fas fa-bell"></i>
            <span v-if="notifications.length" class="button-badge">
                {{ notifications.length }}
            </span>
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

<style>
    .button-badge {
        background-color: #fa3e3e;
        border-radius: 2px;
        color: white;

        padding: .5px 2.5px;
        font-size: 9px;
        position: absolute;
        top: 3px;
        right: 3px;
    }
</style>


