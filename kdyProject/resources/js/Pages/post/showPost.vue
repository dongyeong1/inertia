<template>
  <jet-dialog-modal :show="showOpen">
    <template #title> 상세보기 </template>
    <template #content>
      <div v-if="this.images">
        <img :src="this.imagePath + this.images" />
      </div>
      <div v-for="content in showcontents" :key="content.id">
        <!-- 사용자:{{ content.user.name }}<br /> -->
        제목:{{ content.title }}<br />
        내용:{{ content.content }}<br />
      </div>
      <button @click="getShowContent()">asdasd</button>
      <button @click="$emit('closemodal')">취소</button>
    </template>
  </jet-dialog-modal>
</template>

<script>
import JetDialogModal from "@/Jetstream/DialogModal.vue";
export default {
  props: ["showOpen", "contentId", "images", "showcontents"],
  components: {
    JetDialogModal,
  },
  data() {
    return {
      imagePath: "http://localhost:8000/storage/image/",
      contents: [],
    };
  },
  methods: {
    closeModal() {},
    getShowContent() {
      axios
        .post("/getShowContent", {
          contentId: this.contentId,
        })
        .then((res) => {
          console.log("dog");
          console.log(res.data);
          this.contents = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  created() {},
};
</script>