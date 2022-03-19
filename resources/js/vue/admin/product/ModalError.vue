<template>
  <div>
    <v-dialog name="example">
      This is an example
      <button @click="$modal.hide('foo')">❌</button>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: "ModalError",
  props: {
    myModal: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      dynamicTitle: "Error",
    };
  },
  methods: {},
};
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
</style>