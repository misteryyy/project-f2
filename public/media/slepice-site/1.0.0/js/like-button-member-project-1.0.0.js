/* Like functionality for the site */
var Models = {
			init: function(config){
				// ajax setup
				$.ajaxSetup({
					url: "/site/index/ajax-index", 
					type: 'GET',
					dataType: 'json',
				});
				
				this.config = config;
				this.bindEvents();
			
			},
				
			bindEvents: function(){
				//this.config.likeMemberBtn.css("color","red");
				this.config.likeProjectBtn.on("click", this.likeProject);	
				this.config.likeMemberBtn.on("click", this.likeMember);					
			},
				
			// likeMember
			likeProject: function(e) {
				var follower_count = $(this).parent().parent().find(".followers").children(".follower-count");
				e.preventDefault(); // prevent default action
				var text_like = '<i class="icon-thumbs-up icon-white"></i>Like';
				var text_unlike = '<i class="icon-thumbs-down icon-white"></i>Dislike';
				var project_id = $(this).attr("id");
				var val = $(this).text();
				console.log(val);
				console.log(text_like);
				if(val == "Like") {
					$(this).html(text_unlike);
					$(this).removeClass("btn-success");
					$(this).addClass("btn-danger");
					
				}else {
					$(this).html(text_like);
					$(this).addClass("btn-success");
					$(this).removeClass("btn-danger");
					
				} 	
					// send data to server
					$.ajax({
						data:   "_method=like-project&&project_id="+project_id, // take data from the form and send it to the method update
					}).done(
							function (results){
								// set new value for followers
								follower_count.html(results.data.count_followers); // number of current friends				
							}
					);			
			},
			// likeMember
			likeMember: function(e) {
				e.preventDefault(); // prevent default action
				var text_like = '<i class="icon-thumbs-up icon-white"></i>Like';
				var text_unlike = '<i class="icon-thumbs-down icon-white"></i>Dislike';
				var user_id = $(this).attr("id");
				var val = $(this).text();
				if(val == "Like") {
					$(this).html(text_unlike);
					$(this).removeClass("btn-success");
					$(this).addClass("btn-danger");
		
				}else {
					$(this).html(text_like);
					$(this).addClass("btn-success");
					$(this).removeClass("btn-danger");
				} 	
					// send data to server
					$.ajax({
						data:   "&_method=like-member&&friend_id="+user_id, // take data from the form and send it to the method update
					}).done(
							function (results){
								// set new value for followers		
							}
					);			
			}
			};
			// like models
			Models.init({
				likeMemberBtn: $(".like-member-btn"), //select a with class 
				likeProjectBtn: $(".like-project-btn"), //select a with class 
			});