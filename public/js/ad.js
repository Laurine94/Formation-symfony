$('#add-image').click(function(){
            //Je récupère le numero des futures champrs que je vais créer
            const index=+$('#widgets-counter').val();
            console.log(index);

            //Je récupère le prototype des entrées
            const tmpl=$('#annonce_images').data('prototype').replace(/_name_/g,index);


            //J'injecte ce code au sein de la div
            $('#annonce_images').append(tmpl);

            $('#widgets-counter').val(index+1);

            //je gère le bouton supprimer
            handleDeleteButtons();

        });

        function handleDeleteButtons(){
            $('button[data-action="delete"]').click(function(){
                const target = this.dataset.target;
                $(target).remove();
            });
        }

        function updateCounter(){
            const count=$('#annonce_images div.form-group').length;

            $('#widgets-counter').val(count);
        }

        updateCounter();

        handleDeleteButtons();