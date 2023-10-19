<script>
export default {
  data() {
    return {
      valid: true,
      brand: {
        name: '',
        letter: '',
        image: '',
        categories: [],
      },
      nameRules: [
        v => !!v || "品牌名称不能为空",
        v => v.length > 1 || "品牌名称至少2位"
      ],
      letterRules: [
        v => !!v || "首字母不能为空",
        v => /^[A-Z]$/.test(v) || "品牌字母只能是一个A~Z的大写字母"
      ]
    }
  },
  methods: {
    submit() {
      // 提交表单
      if (this.$refs.myBrandForm.validate()) {
        const {categories, ...params} = this.brand
        params.cids = categories.map(c => c.id).join()

/*        this.$http.post('/item/brand', params).then(resp => {
            const {status, data} = resp

            if (status === 201) {
              this.$emit("close")
              this.$message.success("add success")
            } else {
              this.$message.error('add fail')
            }
          }
        )*/
        this.$http({
          method: this.isEdit ? 'put' : 'post', // put表示修改, post表示添加
          url: '/item/brand',
          data: params
        }).then(() => {
          // 关闭窗口
          this.$emit("close");
          this.$message.success("保存成功！");
        }).catch(() => {
          this.$message.error("保存失败！");
        });
      }
    },
    clear() {
      // 重置表单
      this.$refs.myBrandForm.reset()
      // 需要手动清空商品分类
      this.brand.categories = []
    }
  },
  props: {
    oldBrand: {
      type: Object
    },
    isEdit: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    oldBrand: {
      handler(val) {
        if (val) {
          this.brand = Object.deepCopy(val)
        } else {
          // 为空，初始化brand
          this.brand = {
            name: '',
            letter: '',
            image: '',
            categories: [],
          }
        }
      },
      deep: true
    }
  }
}
</script>

<template>
  <v-form v-model="valid" ref="myBrandForm">
    <v-text-field v-model="brand.name" label="请输入品牌名称" required :rules="nameRules"/>
    <v-text-field v-model="brand.letter" label="请输入品牌首字母" required :rules="letterRules"/>
    <v-cascader
      url="/item/category/list"
      multiple
      required
      v-model="brand.categories"
      label="请选择商品分类"/>
    <v-layout row>
      <v-flex xs3>
        <span class="subheading">品牌LOGO:</span>
      </v-flex>
      <v-flex>
        <v-upload v-model="brand.image" url="/upload" :multiple="false" :pic-width="250" :pic-height="90"/>
      </v-flex>
    </v-layout>
    <v-layout class="my-4" row>
      <v-spacer/>
      <v-btn @click="submit" color="primary">提交</v-btn>
      <v-btn @click="clear">重置</v-btn>
    </v-layout>
  </v-form>
</template>

<style scoped>

</style>
