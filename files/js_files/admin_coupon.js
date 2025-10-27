// coupon detail show and hide
$(document).ready(function () {
  // Handle coupon detail click
  $(".coupon-detail-open").on("click", function () {
      const couponId = $(this).data("id");

      // Make an AJAX request to fetch coupon details
      $.ajax({
          url: "fetch_coupon_data.php",
          type: "POST",
          data: { id: couponId },
          dataType: "json",
          success: function (response) {
              if (response.status === "success") {
                  const data = response.data;
                  $(".coupon-detail").find("td").eq(0).text(data.coupon_title);
                  $(".coupon-detail").find("td").eq(1).text(data.coupon_code);
                  $(".coupon-detail").find("td").eq(2).text(data.start_date);
                  $(".coupon-detail").find("td").eq(3).text(data.end_date);
                  $(".coupon-detail").find("td").eq(4).text(data.coupon_type);
                  $(".coupon-detail").find("td").eq(5).text(
                      data.coupon_type === "percentage"
                          ? data.coupon_value + "%"
                          : "₹" + data.coupon_value
                  );

                  // Show the coupon detail modal
                  $(".coupon-detail").removeClass("-top-full").addClass("top-0");
              } else {
                  alert(response.message);
              }
          },
          error: function () {
              alert("An error occurred while fetching coupon details.");
          },
      });
  });

  // Close the coupon detail modal
  $(".coupon-detail-close").on("click", function () {
      $(".coupon-detail").removeClass("top-0").addClass("-top-full");
  });
});

//coupon type
const coupon_type_radios = document.querySelectorAll('.coupon-type-radio');
const parcentage= document.querySelector('.parcentage-field');
const value = document.querySelector('.value-field');
coupon_type_radios.forEach((radio) => {
  radio.addEventListener('click', (e) => { 
    if(radio.id == 'percent'){
      parcentage.classList.remove('hidden');
    }else{
      parcentage.classList.add('hidden');
    }
    
    if(radio.id == 'val'){
      value.classList.remove('hidden');
    }else{
      value.classList.add('hidden');
    }
  });
});