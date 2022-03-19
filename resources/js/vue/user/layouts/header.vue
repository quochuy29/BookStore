<template>
  <!-- header section starts  -->

  <header class="header">
    <div class="header-1">
      <a href="#" class="logo"> <i class="fas fa-book"></i> bookly </a>

      <form action="javascript:void(0);" class="search-form">
        <input
          type="search"
          name=""
          placeholder="search here..."
          id="search-box"
          v-model="search"
        />
        <label
          for="search-box"
          class="fas fa-search"
          @click="searchProduct()"
        ></label>
      </form>

      <div class="icons">
        <div id="search-btn" class="fas fa-search"></div>
        <router-link
          :to="{ name: 'wishList' }"
          class="fas fa-heart"
        ></router-link>
        <router-link
          :to="{ name: 'cart' }"
          class="fas fa-shopping-cart"
        ></router-link>
        <span
          class="badge badge-warning"
          id="lblCartCount"
          v-if="counter > 0"
          >{{ counter }}</span
        >
        <div class="user" v-if="token">
          <img
            width="35px"
            class="user-img"
            :src="`/storage/${currentUser.avatar}`"
            alt=""
          />
          <div class="logout-user">
            <a href="javascript:void(0);" @click="Logout" class="logout"
              >Logout</a
            >
            <router-link to="/admin" class="logout">Admin</router-link>
          </div>
        </div>
        <div v-else id="login-btn" class="fas fa-user">
          <div class="menu-user">
            <router-link to="/login">Login</router-link>
            <router-link to="/register">Register</router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="header-2">
      <nav class="navbar-book">
        <router-link class="link-nav" to="/home">home</router-link>
        <router-link class="link-nav" :to="{ name: 'books' }">Book</router-link>
        <router-link class="link-nav" :to="{ name: 'listBlogUser' }"
          >arrivals</router-link
        >
        <router-link class="link-nav" :to="{ name: 'listBlogUser' }"
          >reviews</router-link
        >
        <router-link
          class="link-nav menu-categories"
          :to="{ name: 'listBlogUser' }"
        >
          blogs
          <ul>
            <li v-for="(menu, index) in menuCate" :key="index">
              {{ menu.name }}
            </li>
          </ul>
        </router-link>
      </nav>
    </div>
  </header>

  <!-- header section ends -->
</template>

<script>
export default {
  name: "HeaderStore",
  data() {
    return {
      currentUser: {},
      token: localStorage.getItem("token"),
      counter: sessionStorage.getItem("count"),
      menuCate: [],
      search: "",
    };
  },
  mounted() {
    this.loadMenu();
    this.$root.$on("myEvent", (text) => {
      this.counter = text;
      console.log(text);
    });

    if (this.token) {
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios.get("/api/user").then((response) => {
        this.currentUser = response.data;
      });
    }
  },
  methods: {
    loadMenu() {
      axios.get("/api/danh-muc-bai-viet").then((response) => {
        this.menuCate = response.data;
      });
    },
    Logout() {
      axios.get("/api/auth/logout").then((response) => {
        localStorage.removeItem("token");
        this.$router.push("/");
        this.IsLogin = false;
      });
    },
    searchProduct() {
      axios.get("/api/product?search=" + this.search).then((response) => {
        this.$root.$emit("loadSearch",this.search);
        this.$router.push("/san-pham" + response.data.url);
      });
    },
  },
};
</script>

<style lang="less" scoped>
* {
  transition: all 0.2s linear;
}

.badge {
  padding-left: 9px;
  padding-right: 9px;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
}

.label-warning[href],
.badge-warning[href] {
  background-color: #c67605;
}

#lblCartCount {
  font-size: 12px;
  background: #ff0000;
  color: #fff;
  padding: 0 5px;
  vertical-align: top;
  margin-left: -10px;
}

.user {
  display: inline-block;
  position: relative;
  img {
    border-radius: 50%;
    height: 35px;
    margin-left: 1rem;
    margin-bottom: 10px;
    display: inline-block;
  }
}

.logout-user {
  position: absolute;
  font-size: 1rem;
  a {
    display: none;
  }
}

.user:hover {
  .logout {
    display: block;
  }
}

header {
  .header-1 {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 2.5rem 5rem;
    .logo {
      font-size: 2rem;
      text-transform: capitalize;
      color: #333;
      font-weight: 700;
      i {
        color: #27ae60;
      }
    }
    .search-form {
      height: 3rem;
      width: 28rem;
      display: flex;
      align-items: center;
      border: 1px solid #556666;
      border-radius: 5px;
      input {
        font-size: 1rem;
        padding: 0 1.2rem;
        text-transform: none;
        width: 100%;
        height: 100%;
        border: none;
        color: #333;
        border-radius: 5px;
      }
      label {
        padding-right: 1.5rem;
        margin: auto 0;
      }
    }
    .icons {
      font-size: 1.6rem;
      #search-btn {
        display: none;
      }
      a {
        color: #333;
        margin-left: 1rem;
        &:hover {
          color: #27ae60;
        }
      }
      #login-btn {
        color: #333;
        margin-left: 1rem;
        display: inline;
        position: relative;
        &:hover {
          color: #27ae60;
          .menu-user {
            display: block;
            a {
              font-size: 12px;
              display: block;
            }
          }
        }
        .menu-user {
          padding-top: 10px;
          position: absolute;
          height: 45px;
          top: 25px;
          right: 0;
          display: none;
        }
      }
    }
  }

  .header-2 {
    background: #27ae60;
    padding: 10px 0px;
    nav {
      font-size: 1.2rem;
      text-transform: capitalize;
      text-align: center;
      a {
        color: #fff;
        padding-left: 1.5rem;
        position: relative;
        padding-top: 12px;
        padding-bottom: 12px;
      }
    }
  }
}

ul {
  display: none;
  position: absolute;
  top: 48px;
  left: 0;
  right: 0;
  background-color: #27a357;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
  padding-left: 0;
}

ul li {
  color: #fff;
  padding: 12px 24px;
  text-decoration: none;
  display: block;
  text-align: left;
}

ul li:hover {
  background-color: #ddd;
}
.menu-categories:hover ul {
  display: block;
}

@media all and (max-width: 900px) {
  header {
    .header-1 {
      padding: 2.5rem 1rem;
      position: relative;
      .search-form {
        width: 90%;
        right: 5%;
        position: absolute;
        background: #fff;
        top: -110%;
      }

      .search-form.active {
        top: 101%;
      }

      .icons {
        #search-btn {
          display: inline-block;
        }
      }
    }
  }
}
</style>