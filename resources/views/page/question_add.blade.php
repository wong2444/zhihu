<div ng-controller="QuestionAddController" class="question-add container">
    <div class="card">
        <form ng-submit="Question.add()" name="question_add_form">
            <div class="input-group">
                <label>問題標題</label>
                <input type="text" ng-model="Question.new_question.title" name="title"
                       ng-minlength="5" ng-maxlength="255"    required>
            </div>
            <div class="input-group">
                <label>問題描述</label>
                    <textarea type="text" ng-model="Question.new_question.desc" name="desc">


                    </textarea>
            </div>
            <div class="input-group">
                <button ng-disabled="question_add_form.title.$invalid"

                        class="primary"   type="submit">提交</button>

            </div>
        </form>
    </div>
</div>