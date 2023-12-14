/**
 * Created by TNS Programmer on 2022-03-12.
 */
$(document).ready(function() {
    $('.select2').select2();

    $("#province").change(function(e) {
        e.preventDefault();

        let district = $("#district");
        let administrativePost = $("#administrativePost");

        district.empty();

        $("#province option:selected").each(function() {

                let selectedOption = $(this).text();

                district.append(Object.keys(locations[selectedOption]).map(
                        (data, i) => {
                                return `<option data-province="${selectedOption}">${data}</option>`;
                    }
                ));
    });
    
    $("#district").change(function(e) {
        e.preventDefault();

        let administrativePost = $("#administrativePost");
        
        administrativePost.empty();

         $("#district option:selected").each(function() {

                let selectedDistrict = $(this).text();
                let provinceDistrict = $(this).data("province");

                let administrativePosts = locations[provinceDistrict][selectedDistrict];
                administrativePosts.forEach((data) => {
                    administrativePost.append(`<option>${data}</option>`)
                });
         });        
    });
});

let locations = {
  "Cabo Delgado": {
    "Cidade de Pemba": ["Cidade de Pemba"],
    "Ancuabe": ["Ancuabe", "Metoro", "Meza"],
    "Balama": ["Balama", "Impiri", "Kwekwe", "Mavala"],
    "Chiúre": [
      "Chiúre",
      "Chiúre Velho",
      "Katapua",
      "Mazeze",
      "Namogelia",
      "Ocua"
    ],
    "Ibo": ["Ibo", "Quirimbas"],
    "Macomia": ["Macomia", "Chai", "Mucojo", "Quiterajo"],
    "Mecúfi": ["Mecúfi", "Murrebue"],
    "Meluco": ["Meluco", "Muaguide"],
    "Mocímboa da Praia": ["Mocímboa da Praia", "Diaca", "Mbau"],
    "Montepuez": [
      "Cidade de Montepuez",
      "Mapupulo",
      "Mirate",
      "Nairoto",
      "Namanhumbir"
    ],
    "Mueda": ["Mueda", "Chapa", "Imbuho", "Negomano", "N’Gapa"],
    "Muidumbe": ["Muidumbe", "Chitunda", "Miteda"],
    "Namuno": ["Namuno", "Hucula", "Machoca", "Meloco", "Ncumpe", "Luli"],
    "Nangade": ["Nangade", "Ntamba"],
    "Palma": ["Palma", "Olumbe", "Pundanhar", "Quionga"],
    "Pemba-Metuge": ["Metuge", "Mieze"],
    "Quissanga": ["Quissanga", "Bilibiza", "Mahate"]
  },
  "Zambezia": {
    "Cidade de Quelimane": ["Cidade de Quelimane"],
    "Alto Molócue": ["Alto Molócue", "Nauela"],
    "Chinde": ["Chinde-Sede", "Luabo", "Micaune"],
    "Gilé": ["Gilé", "Alto Ligonha"],
    "Gurué": ["Cidade de Gurué", "Lioma", "Nepuagiua"],
    "Ile": ["Ile", "Mulevala", "Socone"],
    "Inhassunge": ["Mucupia", "Gonhane"],
    "Lugela": ["Lugela", "Tacuane", "Munhamade", "Muabanama"],
    "Maganja da Costa": ["Maganja da Costa", "Bojone", "Mocubela", "Nante"],
    "Milange": ["Milange", "Majaua", "Molumbo", "Mongue"],
    "Mocuba": ["Cidade de Mocuba", "Mugeba", "Namajavira"],
    "Mopeia": ["Mopeia", "Campo"],
    "Morrumbala": ["Morrumbala", "Chire", "Derre", "Megaza"],
    "Namacurra": ["Namacurra", "Mucuse"],
    "Namarroi": ["Namarroi", "Regone"],
    "Nicoadala": ["Nicoadala", "Maquival"],
    "Pebane": ["Pebane", "Mulela Mualama", "Naburi"],
  },
  "Nampula": {
    "Cidade de Nampula": [
      "Urbano Central",
      "Muatala",
      "Muhala",
      "Namikopo",
      "Napipine",
      "Natikire"
    ],
    "Angoche": ["Cidade de Angoche", "Aube", "Namaponda", "Boila - Nametoria"],
    "Eráti": ["Namapa", "Alua", "Namiroa"],
    "Ilha de Moçambique": ["Cidade de Ilha de Moçambique", "Lumbo"],
    "Lalaua": ["Lalaua", "Meti"],
    "Malema": ["Malema", "Chihulo", "Mutuali"],
    "Meconta": ["Meconta", "Corrane", "Namialo", "7 de Abril"],
    "Mecubúri": ["Mecubúri", "Milhana", "Muite", "Namina"],
    "Memba": ["Memba", "Chipene", "Lurio", "Mazue"],
    "Mogincual": ["Mogincuala", "Quinga", "Chunga", "Quixaxe", "Liupo"],
    "Mogovolas": ["Nametil", "Calipo", "Ilute", "Muatua", "Nanhupo"],
    "Moma": ["Macone", "Chalaua", "Larde", "Mucuali"],
    "Monapo": ["Monapo", "Itoculo", "Netia"],
    "Mossuril": ["Mossuril", "Lunga", "Matibane"],
    "Muecate": ["Muecate", "Imala", "Muculuone"],
    "Murrupula": ["Murrupula", "Chinga", "Nehessine"],
    "Cidade de Nacala Porto": [
      "Urbano Maiaia",
      "Urbano Mutiva",
      "Urbano Muanona"
    ],
    "Nacala-a-Velha": ["Nacala-a-Velha", "Covo"],
    "Nacarôa": ["Nacarôa ", "Intete", "Saua-Saua"],
    "Rapale": ["Rapale", "Anchilo", "Mutivaze", "Namaita"],
    "Ribaué": ["Ribaué", "Kunle", "Iapala"]
  }
};
});
