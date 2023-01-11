// axios import
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = {
    toexport: String,
    entity: String,
  };

  connect() {
    // console.log("adminTable connected");
    this.checkAllTriger = document.querySelector("#checkAllTriger");
    this.moreActions = document.getElementById("moreActions");
    this.filterWrapper = document.getElementById("filterWrapper");
    this.checkboxes = document.querySelectorAll(".action-checkbox");
    this.allData = JSON.parse(this.toexportValue);
    this.theads = document.querySelectorAll("thead th");
  }

  showmoreactions(s) {
    // console.log("showMoreActions");
    if (s === true) {
      this.moreActions.classList.remove("d-none");
    } else {
      this.moreActions.classList.add("d-none");
    }
  }

  checkall() {
    // console.log("checkAll");
    // if trigger is checked
    if (this.checkAllTriger.checked) {
      // loop through all checkboxes
      this.checkboxes.forEach((checkbox) => {
        // if checkbox is not checked
        if (!checkbox.checked) {
          // check it
          checkbox.checked = true;
        }
      });
      this.showmoreactions(true);
    } else {
      // loop through all checkboxes
      this.checkboxes.forEach((checkbox) => {
        // if checkbox is checked
        if (checkbox.checked) {
          // uncheck it
          checkbox.checked = false;
        }
      });
      this.showmoreactions(false);
    }
  }

  check(e) {
    if (e.target.checked) {
      this.showmoreactions(true);
    } else {
      this.showmoreactions(false);
    }
  }

  exportjson() {
    const checked = document.querySelectorAll(".action-checkbox:checked");

    const data = [];
    checked.forEach((checkbox) => {
      this.allData.find((item) => {
        if (item.id == parseInt(checkbox.id)) {
          data.push(item);
        }
      });
    });

    // create new file with data in json format and download it
    const parsedData = JSON.stringify(data);
    const file = new Blob([parsedData], {
      type: "application/json",
    });
    const a = document.createElement("a");
    a.href = URL.createObjectURL(file);
    // set timestamp
    const timestamp = new Date().getTime();
    a.download = `export-${timestamp}.json`;
    a.click();
    // console.log("exportCSV", parsedData);
  }

  exportcsv() {
    const checked = document.querySelectorAll(".action-checkbox:checked");

    const data = [];
    checked.forEach((checkbox) => {
      this.allData.find((item) => {
        if (item.id == parseInt(checkbox.id)) {
          data.push(item);
        }
      });
    });

    // create new file with data in csv format and download it
    const convertedData = convertToCSV(data);
    const file = new Blob([convertedData], {
      type: "text/csv",
    });
    const a = document.createElement("a");
    a.href = URL.createObjectURL(file);
    // set timestamp
    const timestamp = new Date().getTime();
    a.download = `export-${timestamp}.csv`;
    a.click();
    // console.log("exportCSV", convertedData);
  }

  onfilterkeyup(e) {
    // highlight matching text
    const filter = e.target.value;
    const trs = document.querySelectorAll("tbody tr");
    trs.forEach((tr) => {
      const tds = tr.querySelectorAll("td");
      tds.forEach((td) => {
        // get td name and value
        const tdName = td.getAttribute("name");
        if (tdName !== "check" && tdName !== "actions") {
          const text = td.innerText;
          if (text.indexOf(filter) > -1) {
            // highlight matching text
            td.innerHTML = text.replace(
              filter,
              `<span class="bg-warning
                text-dark">${filter}</span>`
            );
          } else {
            // remove highlight
            td.innerHTML = text;
          }
        }
      });
    });
  }

  openfilter(e) {
    // console.log("openFilter", e.target.name);
    if (this.filterWrapper.classList.contains("d-none")) {
      this.filterWrapper.classList.remove("d-none");
      this.filterChoosed = e.target.name;
    }
  }

  closefilter() {
    // console.log("closeFilter");
    if (!this.filterWrapper.classList.contains("d-none")) {
      // clear filter input
      document.getElementById("filterInput").value = "";
      // remove highlight
      const tds = document.querySelectorAll("tbody td");
      tds.forEach((td) => {
        // get td name and value
        const tdName = td.getAttribute("name");
        if (tdName !== "check" && tdName !== "actions") {
          const text = td.innerText;
          td.innerHTML = text;
        }
      });
      // hide it
      this.filterWrapper.classList.add("d-none");
    }
  }

  // deleteselection() {
  //   // get all checked checkboxes
  //   // check if confirmation is true
  //   if (confirm("Are you sure you want to delete selected items?")) {
  //     const checked = document.querySelectorAll(".action-checkbox:checked");
  //     const ids = [];
  //     checked.forEach((checkbox) => {
  //       // concat id to ids array
  //       ids.push(parseInt(checkbox.id));
  //     });

  //     // call delete function
  //     deleteIds(ids, this.entityValue).then((res) => {
  //       console.log("deleteIds", res);
  //       if (res.status === 200) {
  //         // reload page
  //         window.location.reload();
  //       }
  //     });
  //   }
  // }
}

// async function deleteIds(ids, entity) {

//   // const url = `/api/${entity}s/`;
//   // const errors = [];
//   // const success = [];
//   // ids
//   //   .forEach((id) => {
//   //     // axios delete request
//   //     axios
//   //       .delete(url + id)
//   //       .then((res) => {
//   //         const tr = document.getElementById(id);
//   //         tr.remove();
//   //         success.push(id);
//   //       })
//   //       .catch((err) => {
//   //         errors.push(err);
//   //       });
//   //   })
//   //   .then(() => {
//   //     return {
//   //       errors,
//   //       success,
//   //     };
//   //   });
// }

/**
 * convertToCSV
 * @param {*} jsonData
 * @returns
 */
function convertToCSV(jsonData) {
  var csvData = "";
  // first we need to verify that the data is an array of objects
  if (jsonData.length > 0 && typeof jsonData[0] === "object") {
    // get the keys from the first object
    var keys = Object.keys(jsonData[0]);

    // add the keys to the csv data
    csvData += keys.join(",") + "\n";

    // loop through the data and add each object to the csv data
    for (var i = 0; i < jsonData.length; i++) {
      var row = jsonData[i];
      var values = keys.map(function (key) {
        return row[key];
      });
      csvData += values.join(",") + "\n";
    }
  }
  return csvData;
}
