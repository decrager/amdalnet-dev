<template>
  <div class="navbar">
    <hamburger id="hamburger-container" :is-active="sidebar.opened" class="hamburger-container" @toggleClick="toggleSideBar" />

    <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />

    <div class="right-menu">
      <template v-if="device!=='mobile'">
        <lang-select class="right-menu-item hover-effect" />
        <el-dropdown class="right-menu-item hover-effect">
          <el-badge is-dot :hidden="sumNotifUnread > 0">
            <el-button icon="el-icon-bell" type="text" style="cursor: pointer; font-size: 18px; vertical-align: middle; color: white" @click="markAsRead" />
            <!-- <i class="el-icon-bell" style="cursor: pointer; font-size: 18px; vertical-align: middle;" /> -->
          </el-badge>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item v-for="notif in notifications" :key="notif.id">
              {{ notif.data.createdUser.name }} baru saja mendaftar dengan email {{ notif.data.createdUser.email }}
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <search id="header-search" class="right-menu-item" />

        <!-- <screenfull id="screenfull" class="right-menu-item hover-effect" />

        <el-tooltip :content="$t('navbar.size')" effect="dark" placement="bottom">
          <size-select id="size-select" class="right-menu-item hover-effect" />
        </el-tooltip> -->
      </template>
      <el-button type="text" style="cursor: default; font-size: 18px; vertical-align: 15px; margin-right: 10px; color: white;">{{ getRole }}</el-button>
      <el-dropdown class="avatar-container right-menu-item hover-effect" trigger="click">
        <div class="avatar-wrapper">
          <img :src="avatar || 'no-avatar.png'" class="user-avatar" @error="$event.target.src='no-avatar.png'">
          <i class="el-icon-caret-bottom" />
        </div>
        <el-dropdown-menu slot="dropdown">
          <router-link to="/">
            <el-dropdown-item>
              {{ $t('navbar.dashboard') }}
            </el-dropdown-item>
          </router-link>
          <router-link v-show="userId !== null" :to="`/profile/edit`">
            <el-dropdown-item>
              {{ $t('navbar.profile') }}
            </el-dropdown-item>
          </router-link>
          <el-dropdown-item divided>
            <span style="display:block;" @click="logout">{{ $t('navbar.logOut') }}</span>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
// import Echo from 'laravel-echo';
import Resource from '@/api/resource';
const markAsReadResource = new Resource('mark-all-read');
import Breadcrumb from '@/components/Breadcrumb';
import Hamburger from '@/components/Hamburger';
// import Screenfull from '@/components/Screenfull';
// import SizeSelect from '@/components/SizeSelect';
import LangSelect from '@/components/LangSelect';
import Search from '@/components/HeaderSearch';

export default {
  components: {
    Breadcrumb,
    Hamburger,
    // Screenfull,
    // SizeSelect,
    LangSelect,
    Search,
  },
  computed: {
    ...mapGetters([
      'sidebar',
      'name',
      'avatar',
      'device',
      'userId',
      'notifications',
    ]),
    getRole() {
      const roles = this.$store.getters.roles.map(value => {
        if (value === 'initiator'){
          value = 'pemrakarsa';
        } else if (value === 'formulator'){
          value = 'penyusun';
        } else if (value === 'examiner'){
          value = 'ahli';
        }
        return this.$options.filters.uppercaseFirst(value);
      });
      return roles.join(' | ');
    },
    sumNotifUnread(){
      return this.notifications.filter(e => e.read_at !== null).length;
    },
  },
  created(){
    // console.log(this.$store.getters.token);
    // window.Echo.join(`chat`)
    //   .here((users) => {
    //     //
    //   })
    //   .joining((user) => {
    //     console.log(user.name);
    //   })
    //   .leaving((user) => {
    //     console.log(user.name);
    //   })
    //   .error((error) => {
    //     console.error(error);
    //   });
  },
  methods: {
    markAsRead(){
      console.log('test');
      markAsReadResource.get(this.userId);
    },
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar');
    },
    async logout() {
      await this.$store.dispatch('user/logout');
      this.$router.push('/');
    },
  },
};
</script>
<style>
.el-badge__content.is-fixed.is-dot {
  right: 5px;
  top: 10px;
}
</style>
<style lang="scss" scoped>
.navbar {
  height: 50px;
  overflow: hidden;
  position: relative;
  background: #143b17; // #099C4B;
  //margin: 5px;
  //border-radius: 8px;
  box-shadow: 0 1px 4px rgba(0,21,41,.08);
  margin: 0 0.5em 0.3em;
  border-radius: 0.3em;

  .hamburger-container {
    line-height: 46px;
    height: 100%;
    float: left;
    cursor: pointer;
    transition: background .3s;
    -webkit-tap-highlight-color:transparent;

    &:hover {
      background: rgba(0, 0, 0, .025)
    }
  }

  .breadcrumb-container {
    float: left;
  }

  .errLog-container {
    display: inline-block;
    vertical-align: top;
  }

  .right-menu {
    float: right;
    height: 100%;
    line-height: 50px;

    &:focus {
      outline: none;
    }

    .right-menu-item {
      display: inline-block;
      padding: 0 8px;
      height: 100%;
      font-size: 18px;
      /* color: #5a5e66; */
      color: white;
      vertical-align: text-bottom;

      &.hover-effect {
        cursor: pointer;
        transition: background .3s;

        &:hover {
          background: rgba(0, 0, 0, .025)
        }
      }
    }

    .avatar-container {
      margin-right: 30px;

      .avatar-wrapper {
        margin-top: 5px;
        position: relative;

        .user-avatar {
          cursor: pointer;
          width: 40px;
          height: 40px;
          border-radius: 4px;
        }

        .el-icon-caret-bottom {
          cursor: pointer;
          position: absolute;
          right: -20px;
          top: 25px;
          font-size: 12px;
        }
      }
    }
  }
}
</style>
