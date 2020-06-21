Click [here](contribution.md) for directives on how to contribute

# Comments microservice

## Introduction
What does your API do? This is a comment microservice that allows user to create comments, edit comments and also flags comment. Users can also reply comments , flags reply and also upvote and downvote comments and replies. 

## Overview 
Developers should note that all the parameters in the uris are not optional. All body contents should be sent in json. 

## Authentication
What is the preferred way of using the API? This API can be accessed with any programing language

## Error Codes
```
404, 400, 401
```


## Base Url
[https://fgn-comments-service.herokuapp.com/](https://fgn-comments-service.herokuapp.com/)


## POST : `tweet/comment/create`

This route creates comments received on twitter report. The body will carry the owner username and email also the report id

> BODY 

```json
{"report_id":"integer","comment_body": "string", "comment_origin": "string", "comment_owner_username": "string", "comment_owner_email": "string"}
```

Example Request
Default

> Example Request
` Default
```js
var bodyJson = {"report_id": 63,"comment_body": "This is the comment body", "comment_origin": "Twitter", "comment_owner_username": "name of comment twitter user", "comment_owner_email": "twitteruser@gmail.com"};


var requestOptions = {
  method: 'POST',
  body: bodyJson,
  redirect: 'follow'
};

fetch("tweet/comment/create", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
```
> Example Response

```
201
```
**Body Headers (0)**
```json
{"data" :  [],
"message" :  "Comment saved successfully",
"response" : "Created"}
```

## POST : `report/comment/create`

This route creates comments received on report. The body will carry the owner username and email also the report id if the user is not anonymous. If it is anonymous use anonymous as name and anonymous@email.com as email

> BODY 

```json
{"report_id":"integer","comment_body": "string", "comment_origin": "string", "comment_owner_username": "string", "comment_owner_email": "string"}
```

Example Request
Default

> Example Request
` Default
```js
var bodyJson = {
  "report_id": 63,"comment_body": "This is the comment body", "comment_origin": "comment origin", "comment_owner_username": "name of comment owner use anonymous if user is anonymous", "comment_owner_email": "commentownner@gmail.com"};


var requestOptions = {
  method: 'POST',
  body: bodyJson,
  redirect: 'follow'
};

fetch("report/comment/create", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
```
> Example Response

```
201
```
**Body Headers (0)**
```json
{"data" :  [],
"message" :  "Comment saved successfully",
"response" : "Created"}
```


## GET: report/comments/{report_id}

This route returns all the comments in under a report in the database without the flagged comments.

> Example Request
- Default

```js
var raw = "";

var requestOptions = {
  method: 'GET',
  body: raw,
  redirect: 'follow'
};

fetch("report/comments/{report_id}", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
```
> Example Response
```
200
```
**Body Headers (0)**
```json
{"data" : [{ 
        "report_id": "integer",
        "comment_origin" : "string",
        "comment_body" : "string",
        "comment_owner" : "string",
        "votes" : "integer",
        "replies_count" : "integer",
        "upvotes" : "integer",
        "downvotes" : "integer",
    },],
    "message" : "Comment returned successfully",
    "response" : "Ok"
}
```

## DEL: report/comment/{comment_id}

This route deletes a particular user comment

## BODY
```json
{"email": "string"}
```

> Example Request
- Default
```js
var bodyJson = {"email": "commentownwer@gmail.com"};

var requestOptions = {
  method: 'DELETE',
  body: bodyJson,
  redirect: 'follow'
};

fetch("report/comment/{comment_id}", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
  ```
> Example Response
```
200
```
** Body Headers (0)**
```json
{
    "data" : {
        "comment" : "id"
        },
    "message" : "Comment deleted successfully",
    "response" : "Ok"
}
```
## PATCH : reports/comment/vote/{comment_id}

This route modifies the comments vote. User should send vote types between *upvote and *downvote. The body of the call must include the comment id

## BODY
```json
{"vote_type": enum["upvote", "downvote"]}
```

> Example Request
- Default
```js
var bodyJson = {"vote_type": "downvote"}; //for downvote

var bodyJson = {"vote_type": "upvote"}; //for upvote

var requestOptions = {
  method: 'PATCH',
  body: bodyJson,
  redirect: 'follow'
};

fetch("reports/comment/vote/{comment_id}", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
```
> Example Response
202
**Body Headers (0)**
```json
{"data" : [{
    "comment_votes": "integer",
    "upvotes": "integer",
    "downvotes": "integer",
    "'message" : "Comment voted successfully",
    "response" : "Accepted"
    },]
    }
```
## PATCH: reports/comment/flag/{comment_id}

This route flags comment

> BODY 

{"is_flagged": true}

```json
{"is_flagged": true}
```

> Example Request
- Default
```Js
var bodyJson = {"is_flagged": true};

var requestOptions = {
  method: 'PATCH',
  body: bodyJson,
  redirect: 'follow'
};

fetch("reports/comment/flag/{comment_id}", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
  ```

> Example Response
```
202
```

**Body Headers (0)**
```json
{"data":  [],
"comment": 36,
"message": "Comments Flagged Successfully",
"response": "Accepted"}
```

## PATCH: reports/comment/edit/{comment_id}

This routes edits comment

> BODY 
```json
{
  "comment_body": "string",
  "email": "string"
}
```

> Example Request
- Default
```js
var bodyJson = {"comment_body": "Edited comment", "email": "commentedite@email.com"};

var requestOptions = {
  method: 'PATCH',
  body: bodyJson,
  redirect: 'follow'
};

fetch("reports/comment/edit/{comment_id}", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
Example Response
202
Body Headers (0)
"data": [],
"message": "Comment editted successfully",
"response": "Accepted"
```
## PATCH: reports/comment/reply/vote/{reply_id}

Votes reply

> BODY 
```json
{"vote_type": "enum['upvote', 'downvote']"}
```

> Example Request
- Default
```js
var bodyJson = {"vote_type": "upvote"};

var requestOptions = {
  method: 'PATCH',
  body: bodyJson,
  redirect: 'follow'
};

fetch("reports/comment/reply/vote/{reply_id}\n", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
  ```
> Example Response
```
202
```
**Body Headers (0)**
```json
"data" : {
    "message" : "Reply voted successfully",
    "response" : "Ok"
    },
```
## PATCH: reports/comment/edit/reply/{report_id}

This route allows user to edit reply


> BODY
```json 
{"reply_body": "string", "email": "reply_owner_email@email.com"}

```
> Example Request
- Default
```js
var bodyJson = {"reply_body": "edited reply", "email": "reply_owner_email@email.com"};

var requestOptions = {
  method: 'PATCH',
  body: bodyJson,
  redirect: 'follow'
};

fetch("reports/comment/edit/reply/{report_id}", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
  ```
> Example Response
```
202
```
**Body Headers (0)**
```json
{
  "data": [],
  "message": "Reply editted successfully",
  "response": "Accepted",
}
```
## PATCH:  reports/comment/reply/flag/{reply_id}

Flags reply

> BODY 
```json
"is_flagged": true

```
> Example Request
- Default
```js
var bodyJson = {"is_flagged": true};

var requestOptions = {
  method: 'PATCH',
  body: bodyJson,
  redirect: 'follow'
};

fetch("reports/comment/reply/flag/{reply_id}\n", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
```
> Example Response
```
202
```
**Body Headers (0)**
```json
"data": [],
"message" : "Reply flagged successfully",
"response": "Accepted"
```
## GET report/comment/{comment_id}/reply/{reply_id}

Gets all the replies for a comment

> Example Request
- Default
```js
var raw = "";

var requestOptions = {
  method: 'GET',
  body: raw,
  redirect: 'follow'
};

fetch("report/comment/{comment_id}/reply/{reply_id}", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
```
> Example Response
```
200
```
**Body Headers (0)**
```json
{"data" : [{ 
    "report_id": "integer",
    "comment_id": "integer",
    "reply_body": "string",
    "votes": "integer",
    "reply_id": "integer",
    "upvotes": "integer",
    "downvotes": "integer" 
    },],
"message"  : "Replies returned successfully",
"response" : "Ok"
}
```
