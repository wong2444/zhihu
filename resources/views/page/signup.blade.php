<div class="signup container" ng-controller="SignupController">
    <div class="card">
        <h1>注冊</h1>
        {{--[: User.signup_data :]--}}
        <form ng-submit="User.signup()" name="signup_form">
            <div class="input-group">
                <label>用戶名: </label>
                <input type="text" name="username"
                       ng-minlength="4" ng-maxlength="24"
                       ng-model="User.signup_data.username" ng-model-options="{debounce: 500}" required>
                       <!-- {debounce: 500}表示數據等5秒才寫入model -->
            </div>

            <div ng-if="signup_form.username.$touched" class="input-error-set">
                <div ng-if="signup_form.username.$error.required  ">用戶名為必填項</div>
                <div ng-if="signup_form.username.$error.maxlength || signup_form.username.$error.minlength  ">
                    用戶名長度需在4至24位之間</div>

                <div ng-if="User.signup_username_exists">
                    用戶名已存在</div>
            </div>


            <div  class="input-group">
                <label>密碼: </label>
                <input type="password" name="password"
                       ng-minlength="6"
                       ng-maxlength="255"
                       ng-model="User.signup_data.password"
                       required>
            </div>

            <div ng-if="signup_form.password.$touched" class="input-error-set">
                <div ng-if="signup_form.password.$error.required">密碼為必填項</div>
                <div ng-if="signup_form.password.$error.maxlength || signup_form.password.$error.minlength  ">
                    用戶名長度需在6至255位之間</div>

            </div>

            <button type="submit" ng-disabled="signup_form.$invalid" class="primary">注冊</button>
        </form>
    </div>



</div>