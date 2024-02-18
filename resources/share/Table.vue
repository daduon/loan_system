<template>
      <div>
            <div class="row">
                  <div class="col-lg-6">
                        <el-input v-model="search" style="width: 250px;" class="m-2" :placeholder="`Search by ${searchBy !== '' ? searchBy : 'name'}`" />
                  </div>
                  <div class="col-lg-6 text-end" v-show="btnExport">
                        <el-button type="primary" size="small" @click="exportExcel" plain>Download Excel</el-button>
                        <el-button type="primary" size="small" @click="exportPdf" plain>Download PDF</el-button>
                  </div>
            </div>
            <el-table :data="displayData"
                  :default-sort="tableSort ? { prop: tableSortBy, order: tableSortOrder } : { prop: '', order: '' }"
                  :height="tableHeight" :table-layout="tableLayout" highlight-current-row @row-click="onRowClick"
                  v-loading="loading">
                  <el-table-column class="tw-cursor-pointer" v-for="column in columns" :key="column.label"
                        :label="column.label" :prop="column.prop" :formatter="column.formatter" :min-width="column.minWidth"
                        :sortable="column.sortable">
                        <template #default="scope">
                              <div v-if="column.funcProp" v-html="column.funcProp(scope.row)"></div>
                        </template>
                  </el-table-column>
                  <el-table-column width="150" align="right">
                        <template #header>
                              <label>Action</label>
                        </template>
                        <template #default="scope">
                              <el-button size="small" @click="handleEdit(scope.row)">Edit</el-button>
                              <el-button size="small" type="danger" @click="handleDelete(scope.row)">Delete</el-button>
                        </template>
                  </el-table-column>
            </el-table>

            <div class="tw-mt-4">
                  <el-pagination background @size-change="changePerPage" @current-change="changeCurrentPage"
                        @currentPage="currentPage" :page-size="perPage" :page-sizes="[10, 20, 30, 40, 50, 100]"
                        layout="total, sizes, prev, pager, next, jumper" :total="total">
                  </el-pagination>
            </div>
      </div>
</template>
    
<script lang="ts">
import { defineComponent } from 'vue';
import { exportExcel, exportPDF } from "../js/services/export";

export default defineComponent({
      props: {
            data: Array as any,
            columns: Array as any,
            tableLayout: {
                  type: String,
                  default: 'auto',
                  required: false,
            },
            tableSort: {
                  type: Boolean,
                  default: false,
                  required: false,
            },
            tableSortBy: {
                  type: String,
                  default: '',
                  required: false,
            },
            tableSortOrder: {
                  type: String,
                  default: 'asc',
                  required: false,
            },
            tableHeight: {
                  type: String,
                  default: '40vh',
                  required: false,
            },
            searchBy: {
                  type: Array as any,
                  default: ['name'],
                  required: false,
            },
            loading: {
                  type: Boolean,
                  default: false,
                  required: false,
            },
            btnExport:{
                  type: Boolean,
                  default: false,
                  required: false,
            }
      },
      data() {
            return {
                  filtered: [] as any,
                  search: '',
                  page: 1,
                  currentPage: 1,
                  perPage:10,
                  total: 0,
            }
      },

      computed: {
            displayData() {
                  if (!this.search) return this.data;

                  this.filtered = this.data.filter((data) => !this.search || this.searchBy.some((item) => data[item].toString().toLowerCase().includes(this.search.toLowerCase())))

                  this.total = this.filtered.length

                  return this.filtered.slice(this.perPage * this.currentPage - this.perPage, this.perPage * this.currentPage)
            },
      },

      methods: {
            onRowClick(row) {
                  this.$emit('rowclick', row)
            },
            handleEdit(row) {
                  this.$emit('edit', row)
            },
            handleDelete(row) {
                  this.$emit('delete', row)
            },
            changeCurrentPage(val) {
                  this.currentPage = val
            },

            changePerPage(val) {
                  this.perPage = val
            },
            exportExcel() {
                  const exportData = {
                        columns: this.columns.map((data: any) => {
                              return {
                                    header: data.label,
                                    dataKey: data.prop,
                              };
                        }),
                        body: this.data,
                        fileName: 'excelFile',
                  };
                  exportExcel(exportData);
            },
            exportPdf(){
                  const exportData = {
                        headerList: this.columns.map((data: any) => {
                              return {
                                    header: data.label,
                                    dataKey: data.prop,
                              };
                        }),
                        list: this.data,
                        fileName: 'pdfFile',
                  };
                  exportPDF(exportData);
            }
      },
});
</script>
    