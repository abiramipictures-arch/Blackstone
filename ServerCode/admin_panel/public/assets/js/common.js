var baseUrl = jQuery('#base_url').val();

/************ chunk video upload (320 px)*******/
var datafile = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',
    browse_button: 'uploadFile', // you can pass in id...
    container: document.getElementById('container'), // ... or DOM Element itself
    chunk_size: '1mb',
    url: baseUrl + '/admin/video/saveChunk',
    max_file_count: 1,
    unique_names: true,
    send_file_name: true,
    multi_selection: false,
    filters: {
        mime_types: [
            { title: "Content files", extensions: "mp4" },
        ],
        prevent_duplicates: true
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function () {
            document.getElementById('filelist').innerHTML = '';
            document.getElementById('upload').onclick = function () {
                datafile.start();
                return false;
            };
        },
        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
        },
        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
        FileUploaded: function (up, file, info) {
            var response = JSON.parse(info.response);
            if (response.result) {
                jQuery('#mp3_file_name').val(response.result);
            } else if (file.target_name) {
                jQuery('#mp3_file_name').val(file.target_name);
            }
            toastr.success('Video 320 Uploaded.');
        },
        Error: function (up, err) {
            document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
datafile.init();
/***********************************************/

/************ chunk video upload (480 px)*******/
var datafile1 = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',
    browse_button: 'uploadFile1', // you can pass in id...
    container: document.getElementById('container1'), // ... or DOM Element itself
    chunk_size: '1mb',
    url: baseUrl + '/admin/video/saveChunk',
    max_file_count: 1,
    unique_names: true,
    send_file_name: true,
    multi_selection: false,
    filters: {
        mime_types: [
            { title: "Content files", extensions: "mp4" },
        ],
        prevent_duplicates: true
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function () {
            document.getElementById('filelist1').innerHTML = '';
            document.getElementById('upload1').onclick = function () {
                datafile1.start();
                return false;
            };
        },
        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist1').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
        },
        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
        FileUploaded: function (up, file, info) {
            var response = JSON.parse(info.response);
            if (response.result) {
                jQuery('#mp3_file_name1').val(response.result);
            } else if (file.target_name) {
                jQuery('#mp3_file_name1').val(file.target_name);
            }
            toastr.success('Video 480 Uploaded.');
        },
        Error: function (up, err) {
            document.getElementById('console1').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
datafile1.init();
/***********************************************/

/************ chunk video upload (720 px)*******/
var datafile2 = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',
    browse_button: 'uploadFile2', // you can pass in id...
    container: document.getElementById('container2'), // ... or DOM Element itself
    chunk_size: '1mb',
    url: baseUrl + '/admin/video/saveChunk',
    max_file_count: 1,
    unique_names: true,
    send_file_name: true,
    multi_selection: false,
    filters: {
        mime_types: [
            { title: "Content files", extensions: "mp4" },
        ],
        prevent_duplicates: true
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function () {
            document.getElementById('filelist2').innerHTML = '';
            document.getElementById('upload2').onclick = function () {
                datafile2.start();
                return false;
            };
        },
        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist2').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
        },
        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
        FileUploaded: function (up, file, info) {
            var response = JSON.parse(info.response);
            if (response.result) {
                jQuery('#mp3_file_name2').val(response.result);
            } else if (file.target_name) {
                jQuery('#mp3_file_name2').val(file.target_name);
            }
            toastr.success('Video 720 Uploaded.');
        },
        Error: function (up, err) {
            document.getElementById('console2').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
datafile2.init();
/***********************************************/

/************ chunk video upload (1080 px)******/
var datafile3 = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',
    browse_button: 'uploadFile3', // you can pass in id...
    container: document.getElementById('container3'), // ... or DOM Element itself
    chunk_size: '1mb',
    url: baseUrl + '/admin/video/saveChunk',
    max_file_count: 1,
    unique_names: true,
    send_file_name: true,
    multi_selection: false,
    filters: {
        mime_types: [
            { title: "Content files", extensions: "mp4" },
        ],
        prevent_duplicates: true
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function () {
            document.getElementById('filelist3').innerHTML = '';
            document.getElementById('upload3').onclick = function () {
                datafile3.start();
                return false;
            };
        },
        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist3').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
        },
        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
        FileUploaded: function (up, file, info) {
            var response = JSON.parse(info.response);
            if (response.result) {
                jQuery('#mp3_file_name3').val(response.result);
            } else if (file.target_name) {
                jQuery('#mp3_file_name3').val(file.target_name);
            }
            toastr.success('Video 1080 Uploaded.');
        },
        Error: function (up, err) {
            document.getElementById('console3').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
datafile3.init();
/***********************************************/

/************ Trailer ************************/
var datafile5 = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',
    browse_button: 'uploadFile5', // you can pass in id...
    container: document.getElementById('container5'), // ... or DOM Element itself
    chunk_size: '1mb',
    url: baseUrl + '/admin/video/saveChunk',
    max_file_count: 1,
    unique_names: true,
    send_file_name: true,
    multi_selection: false,
    filters: {
        mime_types: [
            { title: "Content files", extensions: "mp4" },
        ],
        prevent_duplicates: true
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function () {
            document.getElementById('filelist5').innerHTML = '';
            document.getElementById('upload5').onclick = function () {
                datafile5.start();
                return false;
            };
        },
        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist5').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
        },
        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
        FileUploaded: function (up, file, info) {
            var response = JSON.parse(info.response);
            if (response.result) {
                jQuery('#mp3_file_name5').val(response.result);
            } else if (file.target_name) {
                jQuery('#mp3_file_name5').val(file.target_name);
            }
            toastr.success('Trailer Uploaded.');
        },
        Error: function (up, err) {
            document.getElementById('console5').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
datafile5.init();
/***********************************************/

/************ subtitle_1 ************************/
var datafile4 = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',
    browse_button: 'uploadFile4', // you can pass in id...
    container: document.getElementById('container4'), // ... or DOM Element itself
    chunk_size: '1mb',
    url: baseUrl + '/admin/video/saveChunk',
    max_file_count: 1,
    unique_names: true,
    send_file_name: true,
    multi_selection: false,
    filters: {
        mime_types: [
            { title: "Content files", extensions: "mp4,srt", mime_types: ["video/mp4", "application/x-subrip", "text/plain"] }
        ],
        prevent_duplicates: true
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function () {
            document.getElementById('filelist4').innerHTML = '';
            document.getElementById('upload4').onclick = function () {
                datafile4.start();
                return false;
            };
        },
        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist4').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
        },
        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
        FileUploaded: function (up, file, info) {
            var response = JSON.parse(info.response);
            if (response.result) {
                jQuery('#mp3_file_name4').val(response.result);
            } else if (file.target_name) {
                jQuery('#mp3_file_name4').val(file.target_name);
            }
            toastr.success('Subtitle 1 Uploaded.');
        },
        Error: function (up, err) {
            document.getElementById('console4').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
datafile4.init();
/***********************************************/

/************ subtitle_2 ************************/
var datafile6 = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',
    browse_button: 'uploadFile6', // you can pass in id...
    container: document.getElementById('container6'), // ... or DOM Element itself
    chunk_size: '1mb',
    url: baseUrl + '/admin/video/saveChunk',
    max_file_count: 1,
    unique_names: true,
    send_file_name: true,
    multi_selection: false,
    filters: {
        mime_types: [
            { title: "Content files", extensions: "mp4,srt", mime_types: ["video/mp4", "application/x-subrip", "text/plain"] }
        ],
        prevent_duplicates: true
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function () {
            document.getElementById('filelist6').innerHTML = '';
            document.getElementById('upload6').onclick = function () {
                datafile6.start();
                return false;
            };
        },
        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist6').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
        },
        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
        FileUploaded: function (up, file, info) {
            var response = JSON.parse(info.response);
            if (response.result) {
                jQuery('#mp3_file_name6').val(response.result);
            } else if (file.target_name) {
                jQuery('#mp3_file_name6').val(file.target_name);
            }
            toastr.success('Subtitle 2 Uploaded.');
        },
        Error: function (up, err) {
            document.getElementById('console6').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
datafile6.init();
/***********************************************/

/************ subtitle_3 ************************/
var datafile7 = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',
    browse_button: 'uploadFile7', // you can pass in id...
    container: document.getElementById('container7'), // ... or DOM Element itself
    chunk_size: '1mb',
    url: baseUrl + '/admin/video/saveChunk',
    max_file_count: 1,
    unique_names: true,
    send_file_name: true,
    multi_selection: false,
    filters: {
        mime_types: [
            { title: "Content files", extensions: "mp4,srt", mime_types: ["video/mp4", "application/x-subrip", "text/plain"] }
        ],
        prevent_duplicates: true
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function () {
            document.getElementById('filelist7').innerHTML = '';
            document.getElementById('upload7').onclick = function () {
                datafile7.start();
                return false;
            };
        },
        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist7').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
        },
        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },
        FileUploaded: function (up, file, info) {
            var response = JSON.parse(info.response);
            if (response.result) {
                jQuery('#mp3_file_name7').val(response.result);
            } else if (file.target_name) {
                jQuery('#mp3_file_name7').val(file.target_name);
            }
            toastr.success('Subtitle 2 Uploaded.');
        },
        Error: function (up, err) {
            document.getElementById('console7').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});
datafile7.init();
/***********************************************/