$(document).ready(function() {
    $('#phonenumber').inputmask({
      mask: '9999999999',
      placeholder: ''
    });
    $('#cardnumber').inputmask({
      mask: '9999 9999 9999 9999',
      placeholder: ''
    });

    $('#expirydate').inputmask({
      mask: '99/99',
      placeholder: ''
    });
    $('#cardnumber1').inputmask({
      mask: '9999 9999 9999 9999',
      placeholder: ''
    });

    $('#expirydate1').inputmask({
      mask: '99/99',
      placeholder: ''
    });
  });