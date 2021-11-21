
1. Meeting rutin
2. quick win
a. static html
  - sebanyak-banyaknya ada, teh husnul
  - generator --> bung
  - form form
b. web gis
c. collaborative
  - diakses 100
  - 


RKA
RKL/RPL

UKL/UPL
- template puluhan
- 
- matriks --> free teks
- 
AMDAL

- struktur
- isian parsial --> narasi
- dokumen
- isian data
- import / export
  - templating
- integrasi
- collaborative
  - comment
  - theme
- 

Onlyoffice:
- user integration
- langguage
- custom toolbar
- protect



        var docEditor;

        var innerAlert = function (message) {
            if (console && console.log)
                console.log(message);
        };

        var onAppReady = function () {  // the application is loaded into the browser
            innerAlert("Document editor ready");
        };

        var onDocumentStateChange = function (event) {  // the document is modified
            var title = document.title.replace(/\*$/g, "");
            document.title = title + (event.data ? "*" : "");
        };

        var onMetaChange = function (event) {  // the meta information of the document is changed via the meta command
            var favorite = !!event.data.favorite;
            var title = document.title.replace(/^\☆/g, "");
            document.title = (favorite ? "☆" : "") + title;
            docEditor.setFavorite(favorite);  // change the Favorite icon state
        };

        var onRequestEditRights = function () {  // the user is trying to switch the document from the viewing into the editing mode
            location.href = location.href.replace(RegExp("mode=view\&?", "i"), "");
        };

        var onRequestHistory = function (event) {  // the user is trying to show the document version history
            var historyObj = [{"changes":null,"key":"172.23.0.1http___localhost_example_files_172.23.0.1_UKL_20UPL_20SPBU_20-_20Edit_20Nafila_edit_20FM.docx1637464264620","version":1,"created":"2021-11-21 3:11:04","user":{"id":"uid-1","name":"John Smith"}}] || null;

            docEditor.refreshHistory(  // show the document version history
                {
                    currentVersion: "1",
                    history: historyObj
                });
        };

        var onRequestHistoryData = function (data) {  // the user is trying to click the specific document version in the document version history
            var version = data.data;
            var historyData = [{"version":1,"key":"172.23.0.1http___localhost_example_files_172.23.0.1_UKL_20UPL_20SPBU_20-_20Edit_20Nafila_edit_20FM.docx1637464264620","url":"http://localhost/example/download?fileName=UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx&useraddress=172.23.0.1"}] || null;

            docEditor.setHistoryData(historyData[version-1]);  // send the link to the document for viewing the version history
        };

        var onRequestHistoryClose = function (event){  // the user is trying to go back to the document from viewing the document version history
            document.location.reload();
        };

        var onError = function (event) {  // an error or some other specific event occurs
            if (event)
                innerAlert(event.data);
        };

        var onOutdatedVersion = function (event) {  // the document is opened for editing with the old document.key value
            location.reload(true);
        };

        // replace the link to the document which contains a bookmark
        var replaceActionLink = function(href, linkParam) {
            var link;
            var actionIndex = href.indexOf("&action=");
            if (actionIndex != -1) {
                var endIndex = href.indexOf("&", actionIndex + "&action=".length);
                if (endIndex != -1) {
                    link = href.substring(0, actionIndex) + href.substring(endIndex) + "&action=" + encodeURIComponent(linkParam);
                } else {
                    link = href.substring(0, actionIndex) + "&action=" + encodeURIComponent(linkParam);
                }
            } else {
                link = href + "&action=" + encodeURIComponent(linkParam);
            }
            return link;
        }

        var onMakeActionLink = function (event) {  // the user is trying to get link for opening the document which contains a bookmark, scrolling to the bookmark position
            var actionData = event.data;
            var linkParam = JSON.stringify(actionData);
            docEditor.setActionLink(replaceActionLink(location.href, linkParam));  // set the link to the document which contains a bookmark
        };

        var onRequestInsertImage = function(event) {  // the user is trying to insert an image by clicking the Image from Storage button
            docEditor.insertImage({  // insert an image into the file
                "c": event.data.c,
                "fileType":"png","url":"http://localhost/example/images/logo.png"
            })
        };

        var onRequestCompareFile = function() {  // the user is trying to select document for comparing by clicking the Document from Storage button
            docEditor.setRevisedFile({"fileType":"docx","url":"http://localhost/example/assets/sample/sample.docx"});  // select a document for comparing
        };

        var onRequestMailMergeRecipients = function (event) {  // the user is trying to select recipients data by clicking the Mail merge button
            docEditor.setMailMergeRecipients({"fileType":"csv","url":"http://localhost/example/csv"});  // insert recipient data for mail merge into the file
        };

        var onRequestUsers = function () {  // add mentions for not anonymous users
            docEditor.setUsers({  // set a list of users to mention in the comments
                "users": [{"name":"Mark Pottato","email":"pottato@example.com"},{"name":"Hamish Mitchell","email":"mitchell@example.com"}]
            });
        };

        var onRequestSendNotify = function(event) {  // the user is mentioned in a comment
            var actionLink = JSON.stringify(event.data.actionLink);
            console.log("onRequestSendNotify:");
            console.log(event.data);
            console.log("Link to comment: " + replaceActionLink(location.href, actionLink));
        };

        var events = {
            "onAppReady": onAppReady,
            "onDocumentStateChange": onDocumentStateChange,
            "onRequestEditRights": onRequestEditRights,
            "onError": onError,
            "onRequestHistory":  onRequestHistory,
            "onRequestHistoryData": onRequestHistoryData,
            "onRequestHistoryClose": onRequestHistoryClose,
            "onOutdatedVersion": onOutdatedVersion,
            "onMakeActionLink": onMakeActionLink,
            "onMetaChange": onMetaChange,
            "onRequestInsertImage": onRequestInsertImage,
            "onRequestCompareFile": onRequestCompareFile,
            "onRequestMailMergeRecipients": onRequestMailMergeRecipients,
        };

var config = 
{
  "width": "100%",
  "height": "100%",
  "type": "mobile",
  "documentType": "word",
  "token": "",
  "document": {
    "title": "UKL UPL SPBU - Edit Nafila_edit FM.docx",
    "url": "http://localhost/example/download?fileName=UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx&useraddress=172.23.0.1",
    "fileType": "docx",
    "key": "172.23.0.1http___localhost_example_files_172.23.0.1_UKL_20UPL_20SPBU_20-_20Edit_20Nafila_edit_20FM.docx1637464264620",
    "info": {
      "owner": "Me",
      "uploaded": "Sun Nov 21 2021",
      "favorite": null
    },
    "permissions": {
      "comment": true,
      "copy": true,
      "download": true,
      "edit": true,
      "print": true,
      "fillForms": true,
      "modifyFilter": true,
      "modifyContentControl": true,
      "review": true,
      "reviewGroups": null,
      "commentGroups": {}
    }
  },
  "editorConfig": {
    "actionLink": null,
    "mode": "edit",
    "lang": "id",
    "callbackUrl": "http://localhost/example/track?filename=UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx&useraddress=172.23.0.1",
    "createUrl": "http://localhost/example/editor?fileExt=docx&userid=uid-1&type=undefined&lang=id",
    "templates": [{"image":"","title":"Blank","url":"http://localhost/example/editor?fileExt=docx&userid=uid-1&type=undefined&lang=id"},{"image":"http://localhost/example/images/file_docx.svg","title":"With sample content","url":"http://localhost/example/editor?fileExt=docx&userid=uid-1&type=undefined&lang=id&sample=true"}],
    "user": {
      "group": "",
      "id": "uid-1",
      "name": "John Smith"
    },
    "embedded": {
      "saveUrl": "http://localhost/example/files/172.23.0.1/UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx",
      "embedUrl": "http://localhost/example/files/172.23.0.1/UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx",
      "shareUrl": "http://localhost/example/files/172.23.0.1/UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx",
      "toolbarDocked": "top"
    },
    "customization": {
      "about": true,
      "chat": true,
      "comments": true,
      "feedback": true,
      "forcesave": false,
      "goback": {
          "url": "http://localhost/example/"
      },
      "submitForm": true
    },
    "fileChoiceUrl": "",
    "plugins": {"pluginsData":[]}
  }, 
  events: events
};

        var connectEditor = function () {
            addMentions();
            docEditor = new DocsAPI.DocEditor("iframeEditor", config);
            fixSize();
        };

        var addMentions = function () {
            if ([{"name":"Mark Pottato","email":"pottato@example.com"},{"name":"Hamish Mitchell","email":"mitchell@example.com"}] != null)
            {
                events.onRequestUsers = onRequestUsers;
                events.onRequestSendNotify = onRequestSendNotify;
            }
        };

        // get the editor sizes
        var fixSize = function () {
            var wrapEl = document.getElementsByClassName("form");
            if (wrapEl.length) {
                wrapEl[0].style.height = screen.availHeight + "px";
                window.scrollTo(0, -1);
                wrapEl[0].style.height = window.innerHeight + "px";
            }
        };

        if (window.addEventListener) {
            window.addEventListener("load", connectEditor);
            window.addEventListener("resize", fixSize);
        } else if (window.attachEvent) {
            window.attachEvent("onload", conn…

