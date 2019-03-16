<template>
    <div class="dropdown">
        <button class="btn nav-link text-light fa-lg" 
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
                    @click="markAsRead(notification)"
                    :title="notification.data.reply_owner + ' ' + notification.data.action + ' ' + notification.data.thread_name"
                >
                    {{ notification.data.reply_owner }}
                    <strong>{{ notification.data.action }}</strong>
                    {{ notification.data.thread_name.substring(0, 30) }}...
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: [],
                title: ''
            }
        },
        created() {
            this.title = document.title;

            axios.get(`/profiles/${window.App.user.username}/notifications`)
                .then(response => {
                    this.notifications = response.data;
                    this.updateTitle();
                });

            window.Echo.private(`App.User.${window.App.user.id}`)
                .notification((notification) => {
                    this.notifications.unshift(notification);
                    this.updateTitle();
                });
        },
        methods: {
            markAsRead(notification) {
                axios.delete(`/profiles/${window.App.user.username}/notifications/${notification.id}`)
                    .then(() => {
                        this.notifications = this.notifications.filter(n => n.id != notification.id);
                    });
            },
            updateTitle() {
                if (window.App.user && this.notifications.length > 0) {
                    document.title = `(${this.notifications.length}) ${this.title}`
                }
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


