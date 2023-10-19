<template>
  <v-card>
    <v-card-title>
      <v-btn color="primary" @click="addGoods">新增商品</v-btn>
      <!--搜索框，与search属性关联-->
      <v-spacer/>
      <v-text-field label="输入关键字搜索" v-model.lazy="filter.search" append-icon="search" hide-details/>
    </v-card-title>
    <v-flex xs3>
      状态：
      <v-btn-toggle v-model="filter.saleable">
        <v-btn flat>
          全部
        </v-btn>
        <v-btn flat :value="true">
          上架
        </v-btn>
        <v-btn flat :value="false">
          下架
        </v-btn>
      </v-btn-toggle>
    </v-flex>
    <v-divider/>
    <v-data-table
      :headers="headers"
      :items="goodsList"
      :search="filter.search"
      :pagination.sync="pagination"
      :total-items="totalGoods"
      :loading="loading"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.id }}</td>
        <td class="text-xs-center">{{ props.item.title }}</td>
        <td class="text-xs-center">{{ props.item.cname }}</td>
        <td class="text-xs-center">{{ props.item.bname }}</td>
        <td class="justify-center layout">
          <v-btn flat icon color="info" @click="editGoods(props.item)">
            <v-icon>edit</v-icon>
          </v-btn>
          <v-btn flat icon color="warning">
            <v-icon>delete</v-icon>
          </v-btn>
          <v-btn icon>下架</v-btn>
        </td>
      </template>
    </v-data-table>
    <!--弹出的对话框-->
    <v-dialog max-width="700" v-model="show" persistent>
      <v-card>
        <!--对话框的标题-->
        <v-toolbar dense dark color="primary">
          <v-toolbar-title>{{ isEdit ? '修改' : '新增' }}商品</v-toolbar-title>
          <v-spacer/>
          <!--关闭窗口的按钮-->
          <v-btn icon @click="closeWindow">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <!--对话框的内容，表单-->
        <v-card-text class="px-5">
          <goods-form :oldGoods="oldGoods"/>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
// 导入自定义的表单组件
import GoodsForm from './GoodsForm'

export default {
  data() {
    return {
      search: '', // 搜索过滤字段
      totalGoods: 0, // 总条数
      goodsList: [], // 当前页品牌数据
      loading: true, // 是否在加载中
      pagination: {}, // 分页信息
      headers: [
        {text: 'id', align: 'center', value: 'id'},
        {text: '标题', align: 'center', sortable: false, value: 'title'},
        {text: '商品分类', align: 'center', sortable: false, value: 'cname'},
        {text: '品牌', align: 'center', value: 'bname', sortable: false,},
        {text: '操作', align: 'center', sortable: false}
      ],
      show: false,// 控制对话框的显示
      oldGoods: {}, // 即将被编辑的商品信息
      isEdit: false, // 是否是编辑
      filter: {
        saleable: true,
        search: '',
      }
    }
  },
  mounted() { // 渲染后执行
    // 查询数据
    this.getDataFromServer();
  },
  watch: {
    pagination: { // 监视pagination属性的变化
      deep: true, // deep为true，会监视pagination的属性及属性中的对象属性变化
      handler() {
        // 变化后的回调函数，这里我们再次调用getDataFromServer即可
        this.getDataFromServer();
      }
    },
    search: { // 监视搜索字段
      handler() {
        this.getDataFromServer();
      }
    },
    filter: {
      handler() {
        this.getDataFromServer();
      },
      deep: true
    }
  },
  methods: {
    getDataFromServer() { // 从服务的加载数的方法。
      // 发起请求
      this.$http.get("/item/spu/page", {
        params: {
          key: this.search, // 搜索条件
          saleable: this.filter.saleable,
          page: this.pagination.page,// 当前页
          rows: this.pagination.rowsPerPage,// 每页大小
          sortBy: this.pagination.sortBy,// 排序字段
          desc: this.pagination.descending// 是否降序
        }
      }).then(resp => { // 这里使用箭头函数
        this.goodsList = resp.data.items;
        this.totalGoods = resp.data.total;
        // 完成赋值后，把加载状态赋值为false
        this.loading = false;
      })
    },
    addGoods() {
      // 修改标记
      this.isEdit = false;
      // 控制弹窗可见：
      this.show = true;
      // 把oldBrand变为null
      this.oldBrand = null;
    },
    editGoods(oldGoods) {
      // 修改标记
      this.isEdit = true;
      // 控制弹窗可见：
      this.show = true;
      // 获取要编辑的brand
      this.oldGoods = oldGoods;
    },
    closeWindow() {
      // 重新加载数据
      this.getDataFromServer();
      // 关闭窗口
      this.show = false;
    }
  },
  components: {
    GoodsForm
  }
}
</script>

<style scoped>

</style>
