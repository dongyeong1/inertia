<template>
  <jet-dialog-modal :show="isOpen">
    <template #title> Delete Account </template>

    <template #content>
      <div class="originImage" v-if="this.localimage">
        <img :src="imagePath + this.localimage" /> <img />
      </div>
      <div v-if="!image">
        <input
          ref="productImage"
          type="file"
          @change="onFileChange"
          name="imgfile"
          enctype="multipart/form-data"
        />
      </div>
      <div v-else>
        <div class="product-image">
          <img :src="image" />
        </div>
        <button type="button" @click="removeImage">Remove image</button>
      </div>

      제목:<input
        type="text"
        v-model="updatedContent"
        placeholder="제목을 입력해주세요"
      /><input /> 내용:<input
        type="text"
        v-model="updatedTitle"
        placeholder="내용을 입력해주세요"
      />
      <input />
    </template>
    <template #footer>
      <button text @click="update()">수정하기</button>
      <button @click="$emit('close')">취소</button>
    </template>
  </jet-dialog-modal>
</template>

<script>
import JetDialogModal from "@/Jetstream/DialogModal.vue";

export default {
  props: ["isOpen", "contentId", "images"],
  data() {
    return {
      imagePath: "http://localhost:8000/storage/image/",
      image: "",
      deleteImage: "",
      imageFile: "",
      updatedTitle: "",
      updatedContent: "",
      localimage: "",
    };
  },
  components: {
    JetDialogModal,
  },

  methods: {
    closeModal() {
      this.isOpen = false;
    },
    update() {
      const form = new FormData();
      form.append("updatedContent", this.updatedContent);
      form.append("updatedTitle", this.updatedTitle);
      form.append("contentId", this.contentId);
      form.append("imageFile", this.imageFile);
      form.append("deleteImage", this.deleteImage);
      axios
        .post("/update", form)
        .then(() => {
          alert("suceess");
          console.log(typeof this.isOpen);
          this.$emit("close");
          this.$emit("updated");
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onFileChange(e) {
      console.log("updated");
      // console.log(this.productImage);
      console.log(this.images);
      this.deleteImage = this.localimage;
      console.log("asddddddddddddddddddddddd");
      // this.$emit("ImageNull");
      this.localimage = null;
      console.log("dongssss");

      console.log(this.$refs.productImage.files);
      this.imageFile = this.$refs.productImage.files[0];
      console.log("imagefile" + this.imageFile);
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    createImage(file) {
      var reader = new FileReader();
      var vm = this;
      reader.readAsDataURL(file);
      reader.onload = (e) => {
        console.log(e.target.result);
        vm.image = e.target.result;
      };
      // const aa =
      // reader.readAsText(file);
      console.log("asdasdad");
      // console.log(aa + "동영");
    },
    removeImage: function () {
      this.image = "";
    },
  },
  created() {
    this.localimage = this.images;
  },
};
</script>

<style scoped>
.originImage {
  width: 20%;
}
.product-image {
  width: 20%;
}
</style>