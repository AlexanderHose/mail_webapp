import datetime
import email
import imaplib
import os
import sys
import argparse
import urllib

from socket import gaierror
from bs4 import BeautifulSoup

mailbox = "INBOX"
folderContent = ""
emailaddr = ""
password = ""
IMAPserver = ""
filename_mail = ""


def read_arguments():
    global emailaddr, password, IMAPserver, folderContent, filename_mail
    parser = argparse.ArgumentParser()
    parser.add_argument("-e", type=str)
    parser.add_argument("-p", type=str)
    parser.add_argument("-s", type=str)
    parser.add_argument("--path", type=str)
    emailaddr = str(parser.parse_args().e)
    for e in emailaddr:
        if(e.isalnum()):
            filename_mail += e
    password = str(parser.parse_args().p)
    IMAPserver = str(parser.parse_args().s)
    folderContent = "../content/" + str(parser.parse_args().path)

def login_mail():
    try:
        mail = imaplib.IMAP4_SSL(IMAPserver)
        mail.login(emailaddr, password)
    except imaplib.IMAP4.error:
        print("Login failed")
        return
    except gaierror:
        print("IMAP Server error")
        return
    rv, mailboxes = mail.list()
    rv, data = mail.select(mailbox, readonly=True)
    if rv == 'OK':
        process_mailbox(mail)
        mail.close()
    mail.logout()


def process_mailbox(mail):
    rv, data = mail.search(None, "UNSEEN")
    if rv != 'OK':
        print("No messages found!")
        return
    if not os.path.exists(folderContent):
        os.makedirs(folderContent)
    for num in data[0].split():
        rv, data = mail.fetch(num, '(RFC822)')
        email_msg = email.message_from_bytes(data[0][1])
        emailDate = email_msg["Date"]
        emailSubject = email_msg["Subject"]
        emailBody = emailSubject + "::" + emailDate + "::" + IMAPserver + "::"
        try:
            if (email_msg.is_multipart()):
                for payload in email_msg.get_payload():
                    if payload.get_content_type() == 'text/plain':
                        emailBody += BeautifulSoup(payload.get_payload(decode=True).decode("utf-8"),"html.parser").get_text()
                    elif payload.get_content_type() == 'text/html':
                        emailBody += BeautifulSoup(payload.get_payload(decode=True).decode("utf-8"),"html.parser").get_text()
            else:
                emailBody += BeautifulSoup(email_msg.get_payload(decode=True).decode("utf-8"),"html.parser").get_text()
        except UnicodeDecodeError:
            print("Can not decode email")
        file = open(folderContent + "/" + filename_mail + str(num).replace("\'", "") + ".txt", "w", encoding="utf-8")
        file.write(emailBody)
        file.close()
    print("Read all mails")

read_arguments()
login_mail()
