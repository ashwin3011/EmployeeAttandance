// Register to DB //
$("#regpatient").submit(function (e) {
	e.preventDefault();
	var form = $(this);
	$.ajax({
		url: "insert.php",
		type: "POST",
		dataType: 'json',
		data: form.serialize(),
		success: function (data) {
			if (data.status == true) {
				Swal.fire({
					title: 'Registration Success',
					icon: 'success',
					confirmButtonColor: '#5cb85c',
					confirmButtonText: 'OK'
				}).then((result) => {
					if (result.isConfirmed) {
						form.trigger("reset");
						document.getElementById('thumbprint').src = "assets/finger.png";
						document.getElementById('captureproper').style.display = "none";
					}
				})
			} else {
				Swal.fire('Changes are not saved', '', 'info')
			}
		},
		error: function (xhr, status) {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Something went wrong!',
			})
		}
	});
});


var quality = 60; //(1 to 100) (recommanded minimum 55)
var timeout = 10; // seconds (minimum=10(recommanded), maximum=60, unlimited=0 )

window.onload = function (e) {
	//GetInfo();
}

// getinfo //
function GetInfo() {
	// var key = document.getElementById('txtKey').value;
	var key = '';
	var res;
	if (key.length == 0) {
		res = GetMFS100Info();
	} else {
		res = GetMFS100KeyInfo(key);
	}

	if (res.httpStaus) {
		if (res.data.ErrorCode != "0") {
			nodevice();
		}
	} else {
		nodevicesdk();
	}
	return false;
}

function nodevice() {
	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger'
		},
		buttonsStyling: false
	})

	swalWithBootstrapButtons.fire({
		title: 'Device Not Found',
		text: "Solution: Properly connect Device",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Get Support',
		cancelButtonText: 'Try agin',
		reverseButtons: true
	}).then((result) => {
		if (result.isConfirmed) {
			result.dismiss === Swal.DismissReason.cancel;
			window.open("https://api.whatsapp.com/send?phone=9033565294&text='Hello Pdhamecha - BioTeam, I am interested in your biometric system/plateform, Please arrange a quick meeting for more details and demo.Thank You'", "_self");
		} else {
			GetInfo();
		}
	})
}

function nodevicesdk() {
	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger'
		},
		buttonsStyling: false
	})

	swalWithBootstrapButtons.fire({
		title: 'Your System Is Not Register',
		text: "Solution: Register Your Self",
		icon: 'warning',
		showCancelButton: false,
		confirmButtonText: 'Get Support',
		reverseButtons: true
	}).then((result) => {
		if (result.isConfirmed) {
			result.dismiss === Swal.DismissReason.cancel;
			window.open("https://api.whatsapp.com/send?phone=9033565294&text='Hello Pdhamecha - BioTeam, I am interested in your biometric system/plateform, Please arrange a quick meeting for more details and demo.Thank You'", "_self");
		}
	})
}


// Capture ///

function Capture() {
	// try {
	// 	document.getElementById('thumbprint').src = "data:image/bmp;base64,";
	// 	var res = CaptureFinger(quality, timeout);
	// 	console.log(res); // Log the response to debug
	// 	if (res.httpStaus) {
	// 		if (res.data.ErrorCode == "0") {
	// 			document.getElementById('thumbprint').src = "data:image/bmp;base64," + res.data.BitmapData;
	// 			if (res.data.Quality <= 55) {
	// 				document.getElementById('captureproper').style.display = "block";
	// 				document.getElementById('capturebtn').innerHTML = "ReCapture";
	// 			} else {
	// 				document.getElementById('captureproper').style.display = "none";
	// 				document.getElementById('capturebtn').innerHTML = "Capture again";
	// 				document.getElementById('Bitmap').value = res.data.BitmapData;
	// 				document.getElementById('Quality').value = res.data.Quality;
	// 				document.getElementById('Nfic').value = res.data.Nfiq;
	// 				document.getElementById('InWidth').value = res.data.InWidth;
	// 				document.getElementById('InHeight').value = res.data.InHeight;
	// 				document.getElementById('InArea').value = res.data.InArea;
	// 				document.getElementById('Resolution').value = res.data.Resolution;
	// 				document.getElementById('GrayScale').value = res.data.GrayScale;
	// 				document.getElementById('Bpp').value = res.data.Bpp;
	// 				document.getElementById('WsqCompressRatio').value = res.data.WSQCompressRatio;
	// 				document.getElementById('WsqInfo').value = res.data.WSQInfo;
	// 				document.getElementById('IsoTemplate').value = res.data.IsoTemplate;
	// 				document.getElementById('AnsiTemplate').value = res.data.AnsiTemplate;
	// 				document.getElementById('IsoImage').value = res.data.IsoImage;
	// 				document.getElementById('RawData').value = res.data.RawData;
	// 				document.getElementById('WsqImage').value = res.data.WsqImage;
	// 			}
	// 		}
	// 	} else {
	// 		Swal.fire({
	// 			icon: 'error',
	// 			title: 'Oops...',
	// 			text: 'Something went wrong!',
	// 		})
	// 	}
	// } catch (e) {
	// 	alert('Immediatly contact at info@pdhamecha.com');
	// }
	// return false;
	 // Assuming you have a method to capture fingerprint data
    // and retrieve the IsoTemplate and AnsiTemplate

    // Sample code for capturing fingerprint data and setting templates
    // var isoTemplate = "sample_iso_template"; // Replace with actual capture logic
    // var ansiTemplate = "sample_ansi_template"; // Replace with actual capture logic

    // // Set the hidden input values
    // document.getElementById('IsoTemplate').value = isoTemplate;
    // document.getElementById('AnsiTemplate').value = ansiTemplate;

    // // You can now allow form submission
    // return true;
}

// Search //
// function Match() {
// 	try {
// 		document.getElementById('srthumbprint').src = "data:image/bmp;base64,";
// 		var res = CaptureFinger(quality, timeout);
// 		if (res.httpStaus) {
// 			if (res.data.ErrorCode == "0") {
// 				document.getElementById('srthumbprint').src = "data:image/bmp;base64," + res.data.BitmapData;
// 				if (res.data.Quality <= 55) {
// 					document.getElementById('srcapturebtn').style.display = "block";
// 					document.getElementById('srcapturebtn').innerHTML = "ReCapture";
// 				} else {
// 					document.getElementById('srcaptureproper').style.display = "none";
// 					document.getElementById('srcapturebtn').innerHTML = "Searching...";
// 					document.getElementById('srIsoTemplate').value = res.data.IsoTemplate;
// 					document.getElementById('srthumbprint').src = "data:image/bmp;base64," + res.data.BitmapData;
// 				}
// 			}
// 		} else {
// 			Swal.fire({
// 				icon: 'error',
// 				title: 'Oops...',
// 				text: 'Something went wrong!',
// 			})
// 		}
// 	} catch (e) {
// 		alert('Immediatly contact at info@pdhamecha.com');
// 	}

// 	$.ajax({
// 		url: "compare.php",
// 		dataType: 'json',
// 		success: function (ress) {
// 			if (ress.status == true) {
// 				for (var i = 1; i <= ress.count; i++) {
// 					var dbisotemplate = ress.data[i];
// 					var isotemplate = document.getElementById('srIsoTemplate').value;
// 					//var res = MatchFinger(quality, timeout, isotemplate);
// 					var res = VerifyFinger(isotemplate, dbisotemplate);

// 					if (res.httpStaus) {
// 						if (res.data.Status) {
// 							document.getElementById('srName').innerHTML = ress.data[i + 2];
// 							document.getElementById('srContact').innerHTML = ress.data[i + 3];
// 							document.getElementById('srCaseID').innerHTML = ress.data[i + 4];
// 							document.getElementById('srCreateDate').innerHTML = ress.data[i + 5];
// 							Swal.fire({
// 								title: 'Patient Found',
// 								icon: 'success',
// 								confirmButtonColor: '#5cb85c',
// 								confirmButtonText: 'OK'
// 							}).then((result) => {
// 								if (result.isConfirmed) {
// 								}
// 							})
// 							document.getElementById('srcapturebtn').innerHTML = "Search another";
// 							document.getElementById('errorres').style.display = "none";
// 							return;
// 						} else {
// 							document.getElementById('srcapturebtn').innerHTML = "Search another";
// 							document.getElementById('errorres').style.display = "block";
// 						}
// 					} else {
// 						alert(res.err);
// 					}
// 				}
// 			} else {

// 			}
// 		},
// 		error: function (xhr, status) {
// 			Swal.fire({
// 				icon: 'error',
// 				title: 'Oops...',
// 				text: 'Something went wrong!',
// 			})
// 		}
// 	});
// }

function LoginMatch() {
	try {
		document.getElementById('lgnerror').style.display = "none";
		document.getElementById('lgnmatchprnt').src = "data:image/bmp;base64,";
		var res = CaptureFinger(quality, timeout);
		if (res.httpStaus) {
			if (res.data.ErrorCode == "0") {
				document.getElementById('lgnmatchprnt').src = "data:image/bmp;base64," + res.data.BitmapData;
				if (res.data.Quality <= 55) {
					document.getElementById('loginmatch').innerHTML = "ReCapture";
				} else {
					document.getElementById('lgnerror').style.display = "none";
					document.getElementById('lgnIsoTemplate').value = res.data.IsoTemplate;
				}
			}
		} else {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Something went wrong!',
			})
		}
	} catch (e) {
		alert('Immediatly contact at info@pdhamecha.com');
	}

	$.ajax({
		url: "fingerlogin.php",
		dataType: 'json',
		success: function (ress) {
			if (ress.status == true) {
				for (var i = 1; i <= ress.count; i++) {
					var dbisotemplate = ress.data[i];
					var isotemplate = document.getElementById('lgnIsoTemplate').value;
					var res = VerifyFinger(isotemplate, dbisotemplate);
					if (res.httpStaus) {
						if (res.data.Status) {
							Swal.fire({
								title: 'Welcome Dr.Raju',
								icon: 'success',
								confirmButtonColor: '#5cb85c',
								confirmButtonText: 'OK'
							}).then((result) => {
								if (result.isConfirmed) {
									window.location = "dashboard.php";
								}
							})
						} else {
							document.getElementById('lgnerror').style.display = "block";
						}
					} else {
						alert(res.err);
					}
				}
			} else {

			}
		},
		error: function (xhr, status) {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Something went wrong!',
			})
		}
	});
}

// for tabing //
$('#myTab a').on('click', function (e) {
	e.preventDefault()
	$(this).tab('show')
})