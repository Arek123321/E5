using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using static System.Windows.Forms.VisualStyles.VisualStyleElement;
using System.Net.Mime;
using System.Runtime.Remoting.Contexts;
using System.Data.SqlClient;




namespace gsbRapports
{
    public partial class MedGerer : Form
    {
        private gsbrapports2021Entities mesDonneesEF;
        public MedGerer(gsbrapports2021Entities mesDonnees)
        {
           
            InitializeComponent();
            this.mesDonneesEF = mesDonnees;
            this.bindingSource1.DataSource = this.mesDonneesEF.medecin.ToList();
            
           
        }

        private int getNumMed()
        {
            var reqDernier = (from el in this.mesDonneesEF.medecin
                              orderby el.id descending
                              select el);
            medecin dernierMed = reqDernier.First();
            int n = dernierMed.id + 1;
            return n;
        }

        private void bindingNavigatorAddNewItem_Click(object sender, EventArgs e)
        {
            this.textBox1.Text = this.getNumMed().ToString();
        }

        private medecin newMedecin()
        {
          
            medecin newMedecin1= new medecin();
            newMedecin1.nom = textBox2.Text;
            newMedecin1.prenom= textBox3.Text;
            newMedecin1.adresse = textBox4.Text;
            newMedecin1.tel = textBox5.Text;
            newMedecin1.specialiteComplementaire = textBox6.Text;
            newMedecin1.departement = Convert.ToInt32(textBox7.Text);
            return newMedecin1;

        }

        private void toolStripButton1_Click(object sender, EventArgs e)
        {
           

            

            this.bindingSource1.EndEdit();

            int medecinId;
            if (int.TryParse(textBox1.Text, out medecinId))
            {
                var existingMedecin = this.mesDonneesEF.medecin.Find(medecinId);

                if (existingMedecin != null)
                {
                    // Mise a jour
                    existingMedecin.nom = textBox2.Text;
                    existingMedecin.prenom = textBox3.Text;
                    existingMedecin.adresse = textBox4.Text;
                    existingMedecin.tel = textBox5.Text;
                    existingMedecin.specialiteComplementaire = textBox6.Text;
                    existingMedecin.departement = Convert.ToInt32(textBox7.Text);
                }
                else
                {
                    //  nouveau médecin
                    var sql = @"INSERT INTO medecin (nom, prenom, adresse, tel, specialiteComplementaire, departement) 
                        VALUES (@nom, @prenom, @adresse, @tel, @specialiteComplementaire, @departement)";

                    this.mesDonneesEF.Database.ExecuteSqlCommand(sql,
                        new SqlParameter("@nom", textBox2.Text),
                        new SqlParameter("@prenom", textBox3.Text),
                        new SqlParameter("@adresse", textBox4.Text),
                        new SqlParameter("@tel", textBox5.Text),
                        new SqlParameter("@specialiteComplementaire", textBox6.Text),
                        new SqlParameter("@departement", Convert.ToInt32(textBox7.Text)));
                }

                try
                {
                    this.mesDonneesEF.SaveChanges();
                    MessageBox.Show("L'opération s'est terminée avec succès.");
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Une erreur s'est produite lors de la mise à jour de la base de données : " + ex.Message);
                    if (ex.InnerException != null)
                    {
                        MessageBox.Show("Détails de l'erreur interne : " + ex.InnerException.Message);
                    }
                }
            }
            else
            {
                MessageBox.Show("ID fourni n'est pas valide");
            }


        }

        private void bindingNavigatorDeleteItem_Click(object sender, EventArgs e)
        {
            using (var context = new gsbrapports2021Entities())
            {
                var idASupprimer = int.Parse(textBox1.Text);
              
                var entiteASupprimer = context.medecin.FirstOrDefault(vi => vi.id== idASupprimer);
                var rapportsASupprimer = this.mesDonneesEF.rapport.Where(ra => ra.idMedecin == idASupprimer);
            
                int compteur = rapportsASupprimer.Count();
                if (compteur > 0)
                {
                    DialogResult result = MessageBox.Show($"Il y a {compteur} rapport(s) lié(s) à ce visiteur. Êtes-vous sûr de vouloir supprimer ?", "Confirmation", MessageBoxButtons.YesNo);
                    if (result == DialogResult.Yes)
                    {
                        foreach (var unrap in rapportsASupprimer)
                        {
                            var offrirASupprimer = this.mesDonneesEF.offrir.Where(ra => ra.idRapport == unrap.id);
                            foreach(var unoff in offrirASupprimer)
                            {
                                this.mesDonneesEF.offrir.Remove(unoff);
                            }
                            this.mesDonneesEF.rapport.Remove(unrap);

                        }
                        
                        this.mesDonneesEF.SaveChanges();
                        MessageBox.Show("Les rapports ont été supprimés avec succès.");
                        context.medecin.Remove(entiteASupprimer);
                        context.SaveChanges();
                        bindingSource1.RemoveCurrent();
                        MessageBox.Show("Enregistrer supprimé");
                    }
                    else if (result == DialogResult.No)
                    {
                        MessageBox.Show("Suppression annulée.");
                    }
                }
                else if (entiteASupprimer != null)
                {
                    context.medecin.Remove(entiteASupprimer);
                    context.SaveChanges();
                    bindingSource1.RemoveCurrent();
                    MessageBox.Show("Enregistrer supprimé");
                }
            }

        }

       
    }
}
