<html>

<head>
    <title>Welcome</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script language="javascript" type="text/javascript" ></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link href="{!! asset('css/style.css') !!}" type="text/css" rel="stylesheet">
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var questionlist = new Array();
        var questions = 0;

        function getValidationQuestion() {
            var id = sessionStorage.getItem('userId');
            console.log(id);

            $.ajax({
                type: 'post',
                url: "{{ route('admin.getquestion') }}",
                data: {'userId': id},
                success: function (data) {
                    if (data['code'] == '001') {
                        console.log(data['data']);
                        var returnData = data['data'];
                        $('#questionid').val(returnData['id']);
                        $('#question').val(returnData['question'])
                        questionlist.push(returnData['id']);
                        questions += 1;
                    }
                },
                error: function (data) {
                    console.log("Connection failed");
                    console.log(data);
                }
            });
        }

        function validate() {
            console.log($('#questionid').val());
            $.ajax({
                type: 'post',
                url: "{{ route('admin.validatequestion') }}",
                data: {
                    'questionid': $('#questionid').val(),
                    'answer': $('#answer').val()
                },
                success: function (data) {
                    if (data['code'] == '001') {
                        window.location.replace("/transaction");
                    } else {
                        retrieveNewValidation();
                    }
                },
                error: function (data) {
                    console.log("Connection failed");
                    console.log(data);
                }
            })
        }

        function retrieveNewValidation() {
            var id = sessionStorage.getItem('userId');

            $.ajax({
                type: 'post',
                url: "{{ route('admin.questionwithblock') }}",
                data: {
                    'userId': id,
                    'questionlist': questionlist,
                    'questions': questions
                },
                success: function (data) {
                    if (data['code'] == '001') {
                        console.log(data['data']);
                        var returnData = data['data'];
                        $('#questionid').val(returnData['id']);
                        $('#question').val(returnData['question'])
                        questionlist.push(returnData['id']);
                        questions += 1;
                    } else if (data['code'] == '003') {
                        alert(data['message']);
                        window.location.replace('/');
                    }
                },
                error: function (data) {
                    console.log("Connection failed");
                    console.log(data);
                }
            });
        }

        function checkUser() {
            if (!sessionStorage.getItem('username')) {
                alert('You should login first');
                window.location.replace('/');
            } else {
                getValidationQuestion();
            }

        }

    </script>
</head>

<body onload="checkUser()">
<form id="validationform" method="post" action="{{ route('admin.validation') }}">
    <table width="202" border="0" align="center" cellpadding="05" cellspacing="0" id="logintable">
        <tr>
            <td width="192">
                <div class="message">Please answer validation question</div>
            </td>
        </tr>
        <tr>
            <td>
                <input type="hidden" name="questionid" id="questionid" value=""/>
                <input type="text" name="question" id="question" disabled="disabled" value=""/>
            </td>
        </tr>
        <tr>
            <td><input type="answer" name="answer" type="text" id="answer" value="" placeholder="Your answer"></td>
        </tr>
        <tr>
            <td><input type="button" class="submit" value="Login" onclick="validate()"></td>
        </tr>
    </table>
</form>
</body>

</html>