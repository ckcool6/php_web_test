<template>
  <v-card>
    <!-- 卡片的头部 -->
    <v-card-title>
      <v-btn color="primary" @click="show=true">新增</v-btn>
      <v-spacer></v-spacer>
      <v-text-field
        label="请输入关键字搜索"
        v-model="search"
        append-icon="search"
        hide-details
        @keyup.enter="find">
      </v-text-field>
    </v-card-title>
    <!-- 分割线 -->
    <v-divider/>
    <v-data-table
      :headers="headers"
      :items="brands"
      :pagination.sync="pagination"
      :total-items="totalBrands"
      :loading="loading"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.id }}</td>
        <td class="text-xs-center">{{ props.item.name }}</td>
        <td class="text-xs-center">
          <img v-if="props.item.image" :src="props.item.image" width="100">
          <span v-else>没有图片</span>
        </td>
        <td class="text-xs-center">{{ props.item.letter }}</td>
        <td class="text-xs-center">

          <v-btn color="info" @click="editBrand(props.item)">编辑</v-btn>
          <v-btn color="error" @click="deleteBrand">删除</v-btn>

        </td>
      </template>
    </v-data-table>

    <v-dialog max-width="500" v-model="show" persistent>
      <v-card>
        <!--对话框的标题-->
        <v-toolbar dense dark color="primary">
          <v-toolbar-title>新增品牌</v-toolbar-title>
          <v-spacer/>
          <v-btn icon @click="show=false">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <!--对话框的内容，表单-->
        <v-card-text class="px-5">
          <BrandForm @close="closeWindow" :oldBrand="oldBrand"></BrandForm>
        </v-card-text>
      </v-card>
    </v-dialog>

  </v-card>
</template>

<script>
import BrandForm from './BrandForm.vue'

export default {
  data() {
    return {
      headers: [
        // text: 文本信息 value: 字段名 sortable: 是否允许排序,
        {text: 'id', value: 'id', align: 'center'},
        {text: '名称', value: 'name', align: 'center'},
        {text: 'LOGO', value: 'image', align: 'center'},
        {text: '首字母', value: 'letter', align: 'center'},
        {text: '操作', sortable: false, align: 'center'}
      ],
      brands: [], // 数据
      search: '', //搜索的关键字
      pagination: {}, // 分页排序信息
      totalBrands: 0, // 总记录数
      loading: true, // 显示进度条
      show: false,
      oldBrand:{},
    }
  },
  components: {
    BrandForm
  },
  methods: {
    closeWindow() {
      this.show = false
      this.getDataFromServer()
    },
    editBrand(oldBrand) {
      this.show = true
      this.oldBrand = oldBrand
    },
    getDataFromServer() {
      //1. axios.get('url').then(回调函数)
      //2. axios.get('url', {请求参数}).then(回调函数)
      this.$http.get('/item/brand/page', {
        params: {
          key: this.search, // 搜索条件
          page: this.pagination.page,// 当前页
          rows: this.pagination.rowsPerPage,// 每页大小
          sortBy: this.pagination.sortBy,// 排序字段
          desc: this.pagination.descending// 是否降序
        }
      }).then(resp => {
        console.log(resp)
        //const {status, data} = resp
        this.brands = resp.data.items
        this.totalBrands = resp.data.total
        this.loading = false
      })
    },
    find() {
      this.getDataFromServer()
    }
  },
  mounted() {
    // 查询数据
    this.getDataFromServer()
  },
  watch: {
    pagination: {
      deep: true,
      handler() {
        this.getDataFromServer()
      }
    },
  }
}
</script>

<style scoped>

</style>
