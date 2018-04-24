<div ng-controller="LoginController" class="login container">
    <div class="card">
        <h1>登錄</h1>
        <form name="login_form" ng-submit="User.login()">
            <div class="input-group">
                <label>用戶名:</label>
                <input type="text" ng-model="User.login_data.username" required name="username">
            </div>

            <div class="input-group">
                <label>密碼:</label>
                <input type="password" ng-model="User.login_data.password" required name="password">
            </div>



            <div ng-if="User.login_failed" class="input-error-set">用戶名或密碼有誤</div>


            <div class="input-group">

                <button class="primary" type="submit"
                        ng-disabled="login_form.username.$error.required ||
                    login_form.password.$error.required">登錄</button>
            </div>


        </form>
    </div>
</div>