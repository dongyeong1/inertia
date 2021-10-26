<template>
  <app-layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="onSubmitForm">
              <label class="text-xl text-gray-600"
                >Image <span class="text-red-500">*</span></label
              ><br />
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

              <div class="mb-4">
                <label class="text-xl text-gray-600"
                  >Title <span class="text-red-500">*</span></label
                ><br />
                <input
                  v-model="title"
                  type="text"
                  class="border-2 border-gray-300 p-2 w-full"
                  name="title"
                  id="title"
                  required
                />
              </div>

              <div class="mb-8">
                <label class="text-xl text-gray-600"
                  >Content <span class="text-red-500">*</span></label
                ><br />
                <textarea
                  v-model="content"
                  name="content"
                  class="border-2 border-gray-500"
                >
                </textarea>
              </div>

              <div class="flex p-1">
                <button
                  role="submit"
                  class="p-3 bg-blue-500 text-white hover:bg-blue-400"
                  required
                >
                  Submit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div v-for="content in contents" :key="content.id">
      <img style="width: 20%" :src="imagePath + content.image" alt="dd" />
      사용자:{{ content.user.name }}<br />
      제목:{{ content.title }}<br />
      내용:{{ content.content }}<br />
      <button @click="deletes(content.id)">삭제</button>
      <button @click="openModal(content.id)">수정</button>
      <button @click="showPost(content.id)">상세보기</button>
      <button @click="clickHeartLike(content.id)">좋아요{{ this.like }}</button>
      <button @click="clickHeartNotLike(content.id)">
        싫어요{{ this.notlike }}
      </button>
      <show-post
        :showOpen="showopen"
        :contentId="ctId"
        :images="content.image"
        :showcontents="showContents"
        v-on:closemodal="close"
      />

      <edit-post
        v-on:updated="getContent"
        :isOpen="open"
        v-on:close="closeModal"
        :contentId="ctId"
        :images="content.image"
      />
    </div>
  </app-layout>
</template>

<script>
import ShowPost from "@/Pages/post/showPost.vue";
import EditPost from "@/Pages/post/editPost.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

export default {
  data() {
    return {
      like: 0,
      notlike: 0,
      ctId: "",
      imagePath: "http://localhost:8000/storage/image/",
      imageFile: "",
      image: "",
      updatedTitle: "",
      updatedContent: "",
      contents: [],
      title: "",
      content: "",
      open: false,
      showopen: false,
      showContents: [],
    };
  },
  components: {
    EditPost,
    AppLayout,
    ShowPost,

    JetNavLink,
  },
  methods: {
    clickHeartLike(contentId) {
      this.clickLike(contentId);
    },
    clickHeartNotLike(contentId) {
      this.clickNotLike(contentId);
    },
    clickLike(contentId) {
      axios
        .post("/clickLike", {
          contentId,
        })
        .then((res) => {
          this.like = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    clickNotLike(contentId) {
      axios
        .post("/clickNotLike", {
          contentId,
        })
        .then((res) => {
          this.notlike = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    close() {
      this.showopen = false;
    },
    showPost(contentId) {
      this.showopen = true;
      this.ctId = contentId;
      this.getShowContent(contentId);
    },
    getShowContent(contentId) {
      axios
        .post("/getShowContent", {
          contentId,
        })
        .then((res) => {
          console.log("dog");
          console.log(res.data);
          this.showContents = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    ImagesNull(contentId) {
      axios
        .post("/imageDelete", {
          contentId,
        })
        .catch((err) => {
          console.log(err);
        });
    },
    openModal(contentId) {
      alert("sadasd");
      this.open = true;
      this.ctId = contentId;
    },
    closeModal() {
      this.open = false;
    },

    deletes(contentId) {
      if (confirm("진짜삭제할꺼야?")) {
        this.postDelete(contentId);
      }
    },

    postDelete(contentId) {
      axios
        .post("/delete", {
          contentId,
        })
        .then((res) => {
          console.log(res);
          alert("삭제완료");
          this.getContent();
        })
        .catch((err) => {
          console.log(err);
        });
    },

    onSubmitForm() {
      const form = new FormData();
      form.append("title", this.title);
      form.append("content", this.content);
      form.append("imageFile", this.imageFile);
      axios
        .post("/create", form)
        .then((res) => {
          alert("게시물 등록성공");
          this.getContent();
          console.log(res);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onFileChange(e) {
      // console.log(this.productImage);
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
    getContent() {
      axios
        .get("/get")
        .then((res) => {
          console.log("asdadasdasdasdasd");
          console.log(res.data);
          this.contents = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  created() {
    this.getContent();
  },
};
</script>