using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace gsbRapports
{
    public partial class MedListe : Form
    {
        private gsbrapports2021Entities mesDonneesEF;
        public MedListe(gsbrapports2021Entities mesDonnees)
        {
            InitializeComponent();
            this.mesDonneesEF = mesDonnees;
            this.bindingSource1.DataSource = this.mesDonneesEF.medecin.ToList();
        }

        /*private void filtNom()
        {
            var query = from m in mesDonneesEF.Set<medecin>()
                        where m.nom.StartsWith(textBox2.Text)
                        select m;
            bindingSource1.DataSource = query.ToList();

        }

        

        private void filtDep()
        {
            if (int.TryParse(textBox1.Text, out int departmentId))
            {
                var query = from m in mesDonneesEF.Set<medecin>()
                            where m.departement == departmentId
                            select m;
                bindingSource1.DataSource = query.ToList();
            }
            else
            {
                // Handle invalid input in textBox1 (non-integer input)
                // For example, you may want to display a message to the user.
            }
        }*/

        private void ApplyFilters()
        {
            var query = mesDonneesEF.Set<medecin>().AsQueryable();

            if (!string.IsNullOrWhiteSpace(textBox1.Text) && int.TryParse(textBox1.Text, out int departmentId))
            {
                query = query.Where(m => m.departement == departmentId);
            }

            if (!string.IsNullOrWhiteSpace(textBox2.Text))
            {
                query = query.Where(m => m.nom.StartsWith(textBox2.Text));
            }

            bindingSource1.DataSource = query.ToList();
        }

        private string countMedic()
        {
            var query = (from m in mesDonneesEF.Set<medecin>()
                         where m.nom.StartsWith(textBox2.Text)
                         select m.id).Count();
            string result = query.ToString();
            return result;
        }

        private void textBox2_TextChanged(object sender, EventArgs e)
        {


                ApplyFilters();
                dataGridView1.AutoResizeColumns();
               
                
           
        }

        private void textBox1_TextChanged(object sender, EventArgs e)
        {
            ApplyFilters();
            dataGridView1.AutoResizeColumns();

        }



      

    }
}
