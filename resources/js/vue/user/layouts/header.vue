<template>
  <header class="header" @click.stop="showHistory">
    <div class="header-1">
      <router-link :to="{ name: 'homepage' }" class="logo">
        <i class="fas fa-book"></i> bookly
      </router-link>
      <form
        action="javascript:void(0);"
        autocomplete="off"
        class="search-form"
        @click.stop="showSearch"
      >
        <div class="input-search">
          <input
            autocomplete="off"
            type="search"
            name=""
            placeholder="search here..."
            id="search-box"
            v-model="search"
            @change="searchHistory"
          />
          <div class="search-content" v-if="keyWord">
            <div
              class="history-keyword"
              v-for="(keys, index) in keyWordHistory"
              :key="index + '-item'"
            >
              <router-link class="item-history" :to="`san-pham?search=${keys}`">
                <font-awesome-icon :icon="['fas', 'clock-rotate-left']" />
                <div class="keyword">
                  {{ keys }}
                </div>
              </router-link>
              <div class="remove-history" @click.stop="removeKeyword(index)">
                <font-awesome-icon :icon="['fas', 'fa-xmark']" />
              </div>
            </div>
            <div
              class="history-search"
              v-for="(search, index) in contentSearch"
              :key="index"
            >
              <router-link
                :to="{ name: 'detailsBook', params: { name: search.slug } }"
                class="content-history"
              >
                <div class="img-content">
                  <img :src="`/storage/${search.image}`" alt="" />
                </div>
                <div class="text">
                  {{ search.name }}
                </div>
              </router-link>
            </div>
          </div>
        </div>
        <label
          for="search-box"
          class="fas fa-search"
          @click="searchProduct()"
        ></label>
      </form>
      <div class="icons" @click.stop="showHistory">
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
            <router-link v-if="role == 1" to="/admin" class="logout">Admin</router-link>
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
    <div class="header-2" @click="showHistory">
      <nav class="navbar-book">
        <router-link class="link-nav" to="/home">home</router-link>
        <router-link class="link-nav menu-book" :to="{ name: 'books' }"
          >Book
        </router-link>
        <router-link class="link-nav" :to="{ name: 'listBlogUser' }"
          >arrivals</router-link
        >
        <router-link class="link-nav" :to="{ name: 'listBlogUser' }"
          >reviews</router-link
        >
        <router-link
          class="link-nav"
          :to="{ name: 'listBlogUser' }"
        >
          blogs
        </router-link>
      </nav>
    </div>
  </header>
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
      search: this.$route.query.search ? this.$route.query.search : "",
      contentSearch: [],
      keyWordHistory: [],
      keyWord: false,
      role:0
    };
  },
  watch: {
    search: {
      handler: function () {
        this.checkKeyWord();
        this.searchHistory();
      },
    },
  },
  created() {
    if (localStorage.getItem("keywordHistory")) {
      this.keyWordHistory = JSON.parse(localStorage.getItem("keywordHistory"));
    }
  },
  mounted() {
    this.loadMenu();
    this.$root.$on("myEvent", (text) => {
      this.counter = text;
    });

    this.$root.$on("removeSearch", (text) => {
      this.keyWord = text;
    });

    if (this.token) {
      window.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${this.token}`;
      axios.get("/api/user").then((response) => {
        this.currentUser = response.data;
      });
      axios.get("/api/auth/checkRole").then(response=>{
        this.role = response.data.role_id
      })
    }
  },
  methods: {
    showHistory() {
      if (this.keyWord == true) {
        this.keyWord = false;
      }
    },
    showSearch() {
      this.keyWord = true;
    },
    checkKeyWord() {
      this.search !== "" ? (this.keyWord = true) : (this.keyWord = false);
    },
    removeKeyword(id) {
      var keyword = JSON.parse(localStorage.getItem("keywordHistory"));
      var arr = keyword.filter(function (e, index) {
        return index != id;
      });
      localStorage.setItem("keywordHistory", JSON.stringify(arr));
      this.keyWordHistory = JSON.parse(localStorage.getItem("keywordHistory"));
    },
    searchHistory() {
      axios
        .get("/api/product/showSearch?search=" + this.search)
        .then((response) => {
          this.contentSearch = response.data;
        });
    },
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
        if (!localStorage.getItem("keywordHistory")) {
          localStorage.setItem("keywordHistory", JSON.stringify([this.search]));
        } else {
          this.keyWordHistory = JSON.parse(
            localStorage.getItem("keywordHistory")
          );
          this.keyWordHistory.push(this.search);
          var keyWord = this.keyWordHistory.filter(
            (item, index) => this.keyWordHistory.indexOf(item) === index
          );
          localStorage.setItem(
            "keywordHistory",
            JSON.stringify(keyWord)
          );
        }
        this.$root.$emit("loadSearch", this.search);
        this.$router.push("/san-pham" + response.data.url).catch(() => {});
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
.history-keyword {
  display: flex;
  padding: 10px 12px 10px 12px;
  justify-content: space-between;
  align-items: center;
  &:hover {
    background-color: rgba(27, 168, 255, 0.1);
  }
  .item-history {
    display: flex;
    align-items: center;
    flex: 1 1 0%;
    .keyword {
      padding-left: 10px;
    }
  }
  .remove-history {
    padding-right: 10px;
  }
}

.history-search {
  .content-history {
    cursor: pointer;
    display: flex;
    padding: 10px 12px 10px 12px;
    .text {
      padding-left: 10px;
      line-height: 70px;
      flex: 1 1 0%;
    }
    .img-content {
      height: 70px;
      width: 50px;
      img {
        height: 100%;
        width: 100%;
        object-fit: cover;
      }
    }
    &:hover {
      box-shadow: 0 1px 4px 0 rgb(0 0 0 / 26%);
    }
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
      .input-search {
        width: 100%;
        height: 100%;
        position: relative;
        input {
          font-size: 1rem;
          padding: 0 1.2rem;
          text-transform: none;
          width: 100%;
          height: 100%;
          border: none;
          color: #333;
          border-radius: 5px 0 0 5px;
          border-right: 1px solid #333;
        }
        .search-content {
          z-index: 10000;
          position: absolute;
          top: calc(100% + 2px);
          width: 100%;
          box-shadow: 0 1px 4px 0 rgb(0 0 0 / 26%);
          left: 0;
          right: 0;
          background: #fff;
        }
      }
      label {
        padding-right: 1rem;
        padding-left: 1rem;
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
      .link-nav {
        color: #fff;
        padding-left: 1.5rem;
        position: relative;
        padding-top: 12px;
        padding-bottom: 12px;
      }
    }
  }
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