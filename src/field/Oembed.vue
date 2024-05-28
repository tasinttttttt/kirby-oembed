<template>
  <k-field :input="_uid" v-bind="$props" :class="['k-oembed-field', status]">
    <div class="k-input k-oembed-input" data-theme="field">
      <input
        ref="input"
        v-model="url"
        class="k-text-input"
        :disabled="isLoading || disabled"
        :placeholder="$t('oembed.placeholder')"
      />
      <k-button
        theme="positive"
        :class="[{ disabled: !url.length }]"
        :icon="isLoading ? 'refresh' : 'check'"
        :disabled="isLoading"
        @click="onUrlValidate"
      >
        {{ $t("oembed.validate") }}
      </k-button>
      <k-button
        theme="negative"
        :class="[{ disabled: !url.length }]"
        :icon="isLoading ? 'dots' : 'cancel'"
        :disabled="isLoading"
        @click="onDelete"
      >
        {{ $t("oembed.delete") }}
      </k-button>
    </div>

    <k-dialog ref="dialog" @close="error = ''">
      <k-text class="k-error-text">
        {{ $t("oembed.name") }}: {{ $t("oembed.error") }}
        <k-error-details class="k-error-details" v-html="error" />
      </k-text>
      <k-button-group slot="footer">
        <k-button icon="check" @click="$refs.dialog.close()">
          {{ $t("confirm") }}
        </k-button>
      </k-button-group>
    </k-dialog>

    <div class="k-oembed-container">
      <div v-if="embedCode" class="oembed-container">
        <div :id="embedId" class="k-oembed-embed" v-html="embedCode" />
        <div :class="['content', liststyle]">
          <div v-for="(val, propertyName) in sortedInfo" class="content-block">
            <div class="title">
              {{
                ((!$t("oembed.embed." + propertyName)?.includes("oembed") &&
                  $t("oembed.embed." + propertyName)) ||
                  propertyName) | capitalize
              }}
            </div>
            <div class="value">
              {{ val }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <k-empty
      v-if="!embedCode"
      icon="code"
      class="k-oembed-empty"
      @click="$refs.input.focus()"
    >
      {{ $t("oembed.empty") }}
    </k-empty>
  </k-field>
</template>
<script>
export default {
  props: {
    disabled: {
      type: Boolean,
      default: false,
    },
    help: {
      type: String,
      default: "",
    },
    label: {
      type: String,
      default: "",
    },
    liststyle: {
      type: String,
      default: "table",
    },
    name: {
      type: [String, Number],
      default: "",
    },
    required: {
      type: Boolean,
      default: false,
    },
    type: {
      type: String,
      default: "oembed",
    },
    value: {
      type: Array,
      default() {
        return [];
      },
    },
    debug: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      embedCode: undefined,
      error: "",
      filledStatus: "closed",
      info: {},
      isLoading: false,
      limit: 1,
      url: "",
    };
  },
  computed: {
    embedId() {
      return "embed-" + this._uid;
    },
    sortedInfo() {
      const sorted = this.sortObjectKeys(this.info);
      const ordered = Object.assign(
        {},
        {
          title: sorted.title,
          description: sorted.description,
          provider_name: sorted.provider_name,
          provider_url: sorted.provider_url,
          html: sorted.html,
          width: sorted.width,
          height: sorted.height,
          thumbnail_url: sorted.thumbnail_url,
          ...sorted,
        }
      );
      return ordered;
    },
  },
  watch: {
    value(val) {
      this.url = val;
    },
  },
  mounted() {
    this.debug && console.log(this.value);
    if (this.value && this.value.length) {
      this.url = this.value[0];
      if (this.url) {
        this.isLoading = true;
        this.getEmbed(this.url)
          .then((data) => {
            this.updateUi(data);
          })
          .catch((error) => {
            this.error = error;
            this.$refs.dialog.open();
            this.debug && console.warn(error);
          })
          .finally(() => {
            this.isLoading = false;
          });
      }
    }
  },
  methods: {
    sortObjectKeys(obj) {
      return Object.keys(obj)
        .sort()
        .reduce((acc, key) => {
          acc[key] = obj[key];
          return acc;
        }, {});
    },
    onDelete(e) {
      if (e) {
        e.preventDefault();
        e.stopPropagation();
      }
      this.embedCode = undefined;
      this.url = "";
      this.value = "";
      this.$emit("input", this.value);
    },
    onUrlValidate(e) {
      if (e) {
        e.preventDefault();
        e.stopPropagation();
      }
      this.embedCode = undefined;
      this.isLoading = true;
      this.getEmbed(this.url)
        .then((data) => {
          this.updateUi(data);
          this.value = this.url;
          this.$emit("input", this.value);
        })
        .catch((error) => {
          this.error = error;
          this.$refs.dialog.open();
          this.debug && console.warn(error);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    toggle(arg) {
      this.filledStatus = arg;
      this.$nextTick(() => {});
    },
    getEmbed(url) {
      return new Promise((resolve, reject) => {
        if (url) {
          const data = {
            url: url,
          };
          this.$api
            .post("oembed/preview", data)
            .then((response) => {
              if (response && response.data) {
                if (response.data.xdebug_message || response.xdebug_message) {
                  throw Error(
                    response.data.xdebug_message || response.xdebug_message
                  );
                } else {
                  return response.data;
                }
              } else {
                throw Error();
              }
            })
            .then((data) => {
              resolve(data);
            })
            .catch((error) => {
              this.debug &&
                console.log(error, this.$t("oembed.error." + error.message));
              reject(
                (error &&
                  error.message &&
                  this.$t("oembed.error." + error.message)) ||
                  this.$t("oembed.error.generic")
              );
            });
        } else {
          reject(this.$t("oembed.error.no_url_provided"));
        }
      });
    },
    updateUi(data) {
      this.embedCode = data.html;
      this.info = data;
      this.debug && console.log(this.info);
    },
  },
};
</script>

<style lang="scss">
@import "../assets/style/index.scss";
</style>
