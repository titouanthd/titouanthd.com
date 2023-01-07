import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["employer", "companies", "coveringLetterWrapper"];

  print_covering_letter() {
    // get content to print
    const toPrint = this.coveringLetterWrapperTarget;

    // before removing the content from the document body, we need to save the content of the document body
    // so that we can add it back to the document body after printing
    const originalContent = document.body.innerHTML;

    // remove document body content and add the content to print
    document.body.innerHTML = "";
    document.body.appendChild(toPrint);

    window.print();

    // add the original content back to the document body
    document.body.innerHTML = originalContent;
  }

  update_employer(event) {
    const new_employer = event.target.value;

    // Update the employer name in the covering letter
    this.employerTarget.innerText = new_employer;
  }

  update_companies(event) {
    const new_company = event.target.value;

    // for each company in the list companies, update the company name in the covering letter
    this.companiesTargets.forEach((company) => {
      company.innerText = new_company;
    });
  }
}
