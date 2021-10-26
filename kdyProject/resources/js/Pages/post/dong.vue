<template>
  <app-layout>
    <form ref="form" @submit.prevent="onSubmitForm" @keyup.enter="onSubmitForm">
      <div class="mb-4">
        <label
          class="block text-gray-700 text-sm font-bold mb-2"
          for="username"
        >
          번역할것
        </label>
        <input
          class="
            shadow
            appearance-none
            border
            rounded
            w-full
            py-2
            px-3
            text-gray-700
            leading-tight
            focus:outline-none focus:shadow-outline
          "
          id="username"
          type="text"
          placeholder="무엇을 번역할래요??"
          v-model="lang"
        />
      </div>

      <button
        class="
          bg-blue-500
          hover:bg-blue-700
          text-white
          font-bold
          py-2
          px-4
          rounded-full
        "
        type="submit"
      >
        등록
      </button>
    </form>
    <div v-if="tranced">
      {{ tranced }}
    </div>
    <v-btn depressed color="primary"> Primary </v-btn>
    <v-btn depressed color="error"> Error </v-btn>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  props: ["contentId"],
  data() {
    return {
      tranced: "",
      lang: "",
    };
  },
  components: {
    AppLayout,
  },
  methods: {
    onSubmitForm() {
      axios
        .post("/trance", {
          lang: this.lang,
        })
        .then((res) => {
          this.tranced = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  created() {
    console.log("asdsa");
    console.log(this.contentId);
  },
};
</script>