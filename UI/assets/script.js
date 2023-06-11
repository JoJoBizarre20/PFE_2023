function  deleteProject(id){

    deleteFromDb(id,"Id","/deleteProject")
  }
  function requestOptions(data)
  {
    return {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      };
  }
  function readFromDb(url,data,element,renderHTMLfunction){
    
      fetch(url,requestOptions(data)).then(result)
      .then(response => response.json())
      .then((data) =>{
         console.log(data)
             if (data.success) {
                element.innerHTML=renderHTMLfunction(data)
             }
             else{
                 Swal.fire({
                     icon: 'error',
                     title: 'Oops...',
                     text: 'Something went wrong!',
                   })
             }
      })
  }
  
  function changeStatus(statusId,assignmentId)
  {
    fetch ("/updateStatus",requestOptions({statusId:statusId,assignmentId:assignmentId}))
    .then(response => response.json())
    .then((data) =>{
        if(data.success){
            window.location.reload()
        }
    })
  }
  function  deleteAssignment(id){
  
    deleteFromDb(id,"Id","/deleteAssignment")
  }
  function deleteFromDb(val,columnName,url)
  {
    console.log("deleteFromDb")
    let data={}
    data[columnName]=val
    Swal.fire({
        title: 'Are you sure?',
        // text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
              console.log(requestOptions(data));
             fetch(url,requestOptions(data)).then(result)
             .then(response => response.json())
             .then((data) =>{
                console.log(data)
                    if (data.success) {
                        window.location.reload()
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                          })
                    }
             })
        }
      })
  }
  $(document).ready(function(){
    var projectId=""
    var assignmentId=""
    
    $('.modal').modal();
    $('.collapsible').collapsible();
    $('select').formSelect();
    $('.dropdown-trigger').dropdown();
    $('.T').click((e)=>{
        projectId=e.target.getAttribute('data-project-id');
        fetch("/getProject",{
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({Id:projectId})
          }).then(response=>response.json())
          .then(data => {
            console.log(data);
            if(data.projects.length>0){
                $("#Name-update").val( data.projects[0].Name )    
                $("#DueDate-update").val(data.projects[0].DueDate.split(' ')[0]);
            }
        });
    })
    $('.Ta').click((e)=>{
        assignmentId=e.target.getAttribute('data-assignment-id');
        console.log(assignmentId)
        fetch("/getAssignment",{
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({Id:assignmentId})
          }).then(response=>response.json())
          .then(data => {
            console.log(data);
            if(data.assignments.length>0){
                $("#Name-update").val( data.assignments[0].Name )    
                $("#DueDate-update").val(data.assignments[0].DueDate.split(' ')[0]);
            }
        });
    })
    $('form').submit(function(e) {
        e.preventDefault();
        let successMessage = e.target.getAttribute('data-success-msg')
        // Get the submitted form
        var submittedForm = $(this);
        // Get all form values
        var formValues = {};
        submittedForm.find('input, select, textarea').each(function() {
            var inputName = $(this).attr('name')==undefined?'noName': $(this).attr('name');
            var inputValue = $(this).val();
            formValues[inputName] = inputValue;
            formValues["ProjectId"] = projectId;
  
        });
  
        // Log form values to the console
        console.log(formValues);
        console.log(submittedForm[0].name);
        const requestOptions = {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(formValues)
          };
          console.log(requestOptions);
        fetch(submittedForm[0].action, requestOptions)
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            // Handle the response data
            
            
            Swal.fire({
                icon: 'success',
                title: successMessage,
                showConfirmButton: false,
                timer: 1500
              }).then(()=>{
                  window.location.reload();
              })
            
        })
        .catch(error => {
            let failMessage = submittedForm[0].name=="login"?"Fail to log In":"Fail to run Operation"
            console.error('Error:', error);
            // Handle any errors that occurred during the request
            Swal.fire({
                icon: 'error',
                title: failMessage,
                text: error,
              })
        });
    }); 
  })
  
  