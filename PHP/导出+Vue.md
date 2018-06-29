## 导出

**导出类**

```php
<?php

class ExportCsv 
{
    /**
     * 导出csv
     *
     * @param array $data
     * @param array $heads
     * @param string $fileName
     * @return void
     */
    public static function export($data = [], $heads = [], $fileName)
    {
        header('Content-Type: application/vnd.ms-excel;charset=GB2312');
        header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
        header('Cache-Control: max-age=0');
  
        $fp = fopen('php://output', 'a');

        foreach ($heads as $key => $value) {
            $heads[$key] = iconv('utf-8', 'gbk', $value);
        }
  
        fputcsv($fp, $heads);
    
        $num = 0;
    
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 50000;
    
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {

            $num++;
        
            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }

            $row = [];
            foreach ($heads as $key => $value) {
                $row[$key] = isset($data[$i][$key]) ? iconv('utf-8', 'gbk', $data[$i][$key]) : '';
            }

            fputcsv($fp, $row);
        }
        exit();
    }

}

```

**使用实例**

```php
use ExportCsv;
/**
 * 导出
 *
 * @Get('/export')
 */
public function exportCsv()
{
    $conditions = $this->conditions($default);
    $sorts = $this->sorts(array('registered_time' => 'desc'));
    $count = $this->getMerchantStudentService()->count($conditions);
    if ($count > 20000) {
        return ['result' => false];
    }
    $heads = array(  
        'nickname' => '用户名',
        'mobile' => '手机号',
        'agency_nickname' => '代理商账号',
        'registered_time' => '注册时间',
        'coupon_reward' => '注册用户奖励(元)',
        'agency_reward' => '代理商奖励(元)',
    );
    $data = $this->getMerchantStudentService()->search($conditions, $sorts, 0, 20000);
    $exportCsv = [];
    foreach ($data as $export) {
        $export['registered_time'] = date('Y-m-d H:i:s', $export['registered_time']);
        $export['coupon_reward'] = $export['coupon_reward'] / 100;
        $export['agency_reward'] = $export['agency_reward'] / 100;
        $exportCsv[] = $export;
    }
    ExportCsv::export($exportCsv, $heads, '拉新数据-注册用户' . date("Y-m-d"));
}
```

## AJAX导出

由于ajax函数返回类型只有xml,text,json,html等类型,没有流类型,所以通过ajax去请求接口是无法下载文件的。


## vue.js 使用axios实现下载功能的示例

axios如何拦截get请求并下载文件

**Ajax无法下载文件的原因**

* 浏览器的GET(frame、a)和POST(form)请求具有如下特点：

* response会交由浏览器处理

* response内容可以为二进制文件、字符串等

**Ajax请求具有如下特点：**  

* response会交由Javascript处理

* response内容仅可以为字符串

因此，Ajax本身无法触发浏览器的下载功能。

**Axios拦截请求并实现下载**

为了下载文件，我们通常会采用以下步骤：

发送请求

* 获得response

* 通过response判断返回是否为文件

* 如果是文件则在页面中插入frame

* 利用frame实现浏览器的get下载

* 我们可以为axios添加一个拦截器：

```js
import axios from 'axios'
// download url
const downloadUrl = url => {
 let iframe = document.createElement('iframe')
 iframe.style.display = 'none'
 iframe.src = url
 iframe.onload = function () {
 document.body.removeChild(iframe)
 }
 document.body.appendChild(iframe)
}
// Add a response interceptor
axios.interceptors.response.use(c=> {
 // 处理excel文件
 if (res.headers && (res.headers['content-type'] === 'application/x-msdownload' || res.headers['content-type'] === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
 downloadUrl(res.request.responseURL)
 
 <span style="color:#ff0000;"> res.data='';
 res.headers['content-type'] = 'text/json'
 return res;</span>
 }
 ...
 return res;
}, error => {
 <span style="color:#ff0000;">// Do something with response error
 return Promise.reject(error.response.data || error.message)</span>
})
export default axios
```

之后我们就可以通过axios中的get请求下载文件了。

以上这篇vue.js 使用axios实现下载功能的示例


**实测代码(模拟a标签)**

```js
import axios from 'axios'
// download url
const downloadUrl = (url) => {
  let link = document.createElement('a')
  link.href= url;
  link.download = '';
  // 解决firefox不能下载问题
  document.body.appendChild(link);
  link.style.display='none';
  link.click();
}

// Add a response interceptor
axios.interceptors.response.use(res=> {
 // 处理excel文件
 if (res.headers && 
 	(res.headers['content-type'] === 'application/vnd.ms-excel;charset=GB2312')) {
 	downloadUrl(res.request.responseURL)
 }
 return res;
}, error => {
 return Promise.reject(error.response.data || error.message)
})

export default axios
```

## Vue导出实例

**实例**

```vue
<template>
  <div>
    <div class="mtl">
      <el-form :inline="true" :model="searchItem" class="form-inline">
        <div>
          <el-form-item label="关键词:">
            <el-select v-model="searchItem.keywordType" placeholder="--请选择--" clearable>
              <el-option v-for="keywordOption in keywordOptions" :key="keywordOption.value" :label="keywordOption.label"
                         :value="keywordOption.value"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-input v-model="searchItem.keyword" placeholder="请输入内容"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button class="is-plain" size="medium" type="primary" @click="onSearch">搜索</el-button>
            <el-button class="is-plain" size="medium" type="primary" @click="onExport">导出</el-button>
          </el-form-item>
        </div>
      </el-form>
    </div>

    <el-col :span="24" class="mbs">未提现总额：{{ (parseInt(statisticData.sum_amount) + parseInt(statisticData.sum_frozen_amount)) | money }} 元 （含待审核:{{ statisticData.sum_frozen_amount | money }} 元）已提现总额：{{ statisticData.sum_withdraw_amount | money }} 元</el-col>

    <el-table :data="agencyAccounts" :stripe="true" style="width: 100%">
      <el-table-column prod="agency_code" label="代理商账号" align="center" min-width="15%">
        <template slot-scope="scope">
          {{ scope.row.agency_nickname }}
        </template>
      </el-table-column>
      <el-table-column prod="agency_name" label="机构/个人名称" align="center" min-width="15%">
        <template slot-scope="scope">
          {{ scope.row.agency_name }}
        </template>
      </el-table-column>
      <el-table-column prod="mobile" label="联系手机" align="center" min-width="15%">
        <template slot-scope="scope">
          {{ scope.row.mobile }}
        </template>
      </el-table-column>
      <el-table-column label="未提现金额（含待审核）" align="center" min-width="15%">
        <template slot-scope="scope">
          {{ (parseInt(scope.row.amount, 10) + parseInt(scope.row.frozen_amount, 10)) | money}}
        </template>
      </el-table-column>
      <el-table-column prod="withdraw_amount" label="已提现金额" align="center" min-width="15%">
        <template slot-scope="scope">
          {{ (scope.row.withdraw_amount) | money}}
        </template>
      </el-table-column>
      <el-table-column prod="withdraw_last_time" label="最后提现时间" align="center" min-width="15%">
        <template slot-scope="scope">
          {{ scope.row.withdraw_last_time }}
        </template>
      </el-table-column>
      <el-table-column prod="withdraw_times" label="成功提现次数" align="center" min-width="15%">
        <template slot-scope="scope">
          {{ scope.row.withdraw_times }}
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" min-width="15%">
        <template slot-scope="scope">
          <el-button size="mini" class="mrs" type="text" @click="onShowAgencyWithdrawRecords(scope.$index)">提现记录
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <M-paginator :paging="paging" @change="pageChangeListener"></M-paginator>
    <el-dialog
      :visible.sync="agencyAccountWithdrawRecordDialog.show"
      center
      width="50%"
    >
      <el-table :data="agencyAccountWithdrawRecords" :stripe="true" style="width: 100%">
        <el-table-column prod="sn" label="提现单号" align="center" min-width="15%">
          <template slot-scope="scope">
            {{ scope.row.sn }}
          </template>
        </el-table-column>
        <el-table-column prod="agency_code" label="代理商账号" align="center" min-width="15%">
          <template slot-scope="scope">
            {{ scope.row.agency_nickname }}
          </template>
        </el-table-column>
        <el-table-column prod="registered_time" label="提现金额" align="center" min-width="15%">
          <template slot-scope="scope">
            {{ scope.row.amount | money }}
          </template>
        </el-table-column>
        <el-table-column prod="created_time" label="申请时间" align="center" min-width="15%">
          <template slot-scope="scope">
            {{ scope.row.created_time }}
          </template>
        </el-table-column>
        <el-table-column prod="audit_time" label="审核时间" align="center" min-width="20%">
          <template slot-scope="scope">
            {{ scope.row.audit_time }}
          </template>
        </el-table-column>
        <el-table-column prod="status" label="审核状态" align="center" min-width="15%">
          <template slot-scope="scope">
            {{ scope.row.status == 'approving' ? '待审核' : '' }}
            {{ scope.row.status == 'approved' ? '已通过' : '' }}
            {{ scope.row.status == 'rejected' ? '未通过' : '' }}
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>

<script>
  import API from '@/Api';
  import Export from "@/modules/distribution/mixins/export.js";

  export default {
    mixins: [Export],
    data() {
      return {
        statisticData: {
          sum_amount: 0,
          sum_frozen_amount: 0,
          sum_withdraw_amount: 0,
        },
        created_time_range: '',
        searchItem: {
          created_time_range: []
        },
        statusOptions: [
          {
            label: '已通过',
            value: 'approved',
          },
          {
            label: '未通过',
            value: 'rejected',
          },
          {
            label: '待审核',
            value: 'approving',
          }
        ],
        keywordOptions: [
          {
            label: '代理商账号',
            value: 'likeNickname',
          },
          {
            label: '机构/个人名称',
            value: 'likeName',
          },
          {
            label: '联系手机',
            value: 'likeMobile',
          },
        ],
        agencyAccounts: [],
        agencyAccountWithdrawRecords: {},
        agencyAccountWithdrawRecordDialog: {
          show: false
        },
        paging: {
          total: 0,
          offset: 0,
          limit: 20
        }
      }
    },
    created() {
      this.fetchAgencyAccounts();
      this.getStatisticData();
      this.onListen();
    },
    methods: {
      onSearch() {
        API.DRP.Merchant.getAgencyAccounts(this.searchItem).then(data => {
          this.agencyAccounts = data.data;
          this.paging = data.paging;
        });

        this.getStatisticData();
      },
      fetchAgencyAccounts() {
        API.DRP.Merchant.getAgencyAccounts().then(data => {
          this.agencyAccounts = data.data;
          this.paging = data.paging;
        });
      },
      onShowAgencyWithdrawRecords(index) {
        let merchantAgencyId = this.agencyAccounts[index]['merchant_agency_id'];
        API.DRP.Merchant.getAgencyAccountWithdrawRecords(merchantAgencyId).then(data => {
          this.agencyAccountWithdrawRecords = data;
          this.agencyAccountWithdrawRecordDialog.show = true;
        }).catch(error => {
          console.log(error);
        });
      },
      getStatisticData() {
        API.DRP.Merchant.getAgencyAccountStatisticData(this.searchItem).then(({ data }) => {
          this.statisticData = {
            sum_amount: data.sum_amount == null ? 0 : data.sum_amount,
            sum_frozen_amount: data.sum_frozen_amount == null ? 0 : data.sum_frozen_amount,
            sum_withdraw_amount: data.sum_withdraw_amount == null ? 0 : data.sum_withdraw_amount,
          }
        }).catch(error => {
          console.log(error);
        });
      },
      onListen() {
        bus.$on('auditChange', (params) => {
          this.onSearch();
        });
      },
      pageChangeListener(page) {
        API.DRP.Merchant.getAgencyAccounts(this.searchItem, page).then(data => {
          this.agencyAccounts = data.data;
          this.paging = data.paging;
        });
      },
      onExport() {
        const url = '/merchant/accounts/export';
        this.export(url, this.searchItem);
      },
    }
  };
</script>
```

**export.js**

```js
import API from '@/Api';

export default {
	methods: {
		export(url, params) {
			API.DRP.Merchant.export(url, params).then(data => {
		  	console.log(data)
		    if (data.result == false) {
		      this.$message({
		        type: 'warning',
		        message: '目前导出的数据条数已经超过最高导出上限20000条，请您重新选择查询条件后导出。'
		      }); 
		    }  
		  });
		},
	},
};

```

**Mechant.js**

```
import DRPResource from './resource';

 /**
  * 导出
  * @param url
  * $param params
  */
  export(url, params) {
    return DRPResource.get(url, {params});
  }
```

**resource.js**

```js
/**
 * 后台相关的接口的资源请求配置；
 */
import Vue from 'vue';
import axios from 'axios';
import ErrorCode from '../ErrorCode';

const DRPResource = axios.create({
  baseURL: '/drp',
  timeout: 10000,
  headers: {},
  withCredentials: true,
  xsrfCookieName: 'csrf_token',
});

DRPResource.interceptors.response.use((response) => {
  // handler export
  if (response.headers 
    && (response.headers['content-type'] 
    === 'application/vnd.ms-excel;charset=GB2312')) {
    downloadUrl(response.request.responseURL)
  }

  return response.data;
}, (err) => {
  const error = err.response.data.error;
  if (error.code === ErrorCode.BAD_REQUEST) {
    if (bus.userType === 'agency') {
      location.href = `${location.origin}${location.pathname}#/login`;
    }
    if (bus.userType === 'merchant') {
      location.href = `${location.origin}${location.pathname}#/merchant/relogin`;
    }
    return;
  }
  return Promise.reject(error);
});

// download export file
const downloadUrl = (url) => {
  let link = document.createElement('a');
  link.href= url;
  link.download = '';
  // 解决firefox不能下载问题
  document.body.appendChild(link);
  link.style.display='none';
  link.click();
}

export default DRPResource;
```