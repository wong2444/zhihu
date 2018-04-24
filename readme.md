 # 曉乎後端API文檔 v1.0.0
 ## 常規API調用原則
 - 所有API都以`domain.com/api/...`開頭
 - API分成兩個部分,如`domain.com/api/part_1/part_2`
 	- `part_1`為model名稱,如`user`或`question`
 	- `part_2`為行為名稱,如`reset_password`
 - CRUD
 	- 每個model中都會有增刪查改四個方法,分別對應`add`,`remove`,`change`,`read`
 
 ## Model
 ### Question
 #### 字後解釋
 - `id`
 - `title`:標題
 - `desc`:描述
 #### `add`
 - 權限:己登錄
 - 傳參:
 	- 必填:`title`(標題)
 	- 可選:`desc`(描述)
 #### `change`
 - 權限:己登錄且為問題的所有者
 - 傳參:
 	- 必填: `id`(問題id)
 	- 可選: `title`,`desc`
    